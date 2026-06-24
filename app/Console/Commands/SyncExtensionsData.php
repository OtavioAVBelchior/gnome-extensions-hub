<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Extension;
use App\Models\CachedIssue;
use App\Models\CachedMergeRequest;
use Illuminate\Support\Facades\Http;

class SyncExtensionsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'extensions:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync issues and pull/merge requests for all extensions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $extensions = Extension::with('user')->get();

        foreach ($extensions as $extension) {
            $this->info("Syncing extension: {$extension->extension_name}");

            if ($extension->platform === 'github') {
                $this->syncGithub($extension);
            } elseif ($extension->platform === 'gitlab') {
                $this->syncGitlab($extension);
            }
        }

        $this->info('Sync completed.');
    }

    protected function syncGithub(Extension $extension)
    {
        $user = $extension->user;
        if (!$user || !$user->github_token) {
            $this->warn("Skipping GitHub sync for {$extension->extension_name}: User has no github_token");
            return;
        }

        // Fetch Issues & PRs
        $response = Http::withToken($user->github_token)
            ->withHeaders([
                'Accept' => 'application/vnd.github+json',
                'X-GitHub-Api-Version' => '2022-11-28',
            ])
            ->get("https://api.github.com/repos/{$extension->repo_full_name}/issues", [
                'state' => 'all',
                'per_page' => 100,
            ]);

        if ($response->failed()) {
            $this->error("Failed to fetch GitHub issues for {$extension->repo_full_name}");
            return;
        }

        $items = $response->json();

        foreach ($items as $item) {
            $isPullRequest = isset($item['pull_request']);

            $labels = collect($item['labels'])->pluck('name')->toArray();

            if ($isPullRequest) {
                CachedMergeRequest::updateOrCreate(
                    [
                        'extension_id' => $extension->id,
                        'mr_iid' => $item['number'],
                    ],
                    [
                        'platform' => 'github',
                        'title' => $item['title'],
                        'description' => $item['body'] ?? '',
                        'state' => $item['state'],
                        'author' => $item['user']['login'] ?? 'Unknown',
                        'labels' => json_encode($labels),
                        'opened_at' => \Carbon\Carbon::parse($item['created_at']),
                        'last_updated_at' => \Carbon\Carbon::parse($item['updated_at']),
                    ]
                );
            } else {
                CachedIssue::updateOrCreate(
                    [
                        'extension_id' => $extension->id,
                        'issue_iid' => $item['number'],
                    ],
                    [
                        'platform' => 'github',
                        'title' => $item['title'],
                        'description' => $item['body'] ?? '',
                        'state' => $item['state'],
                        'author' => $item['user']['login'] ?? 'Unknown',
                        'labels' => json_encode($labels),
                        'opened_at' => \Carbon\Carbon::parse($item['created_at']),
                        'last_updated_at' => \Carbon\Carbon::parse($item['updated_at']),
                    ]
                );
            }
        }
        
        $this->info("Synced GitHub repository {$extension->repo_full_name}");
    }

    protected function syncGitlab(Extension $extension)
    {
        $user = $extension->user;
        if (!$user || !$user->gitlab_token) {
            $this->warn("Skipping GitLab sync for {$extension->extension_name}: User has no gitlab_token");
            return;
        }

        $encodedRepoName = urlencode($extension->repo_full_name);

        // Fetch Issues
        $issuesResponse = Http::withToken($user->gitlab_token)
            ->get("https://gitlab.com/api/v4/projects/{$encodedRepoName}/issues", [
                'per_page' => 100,
            ]);

        if ($issuesResponse->successful()) {
            foreach ($issuesResponse->json() as $item) {
                CachedIssue::updateOrCreate(
                    [
                        'extension_id' => $extension->id,
                        'issue_iid' => $item['iid'],
                    ],
                    [
                        'platform' => 'gitlab',
                        'title' => $item['title'],
                        'description' => $item['description'] ?? '',
                        'state' => $item['state'],
                        'author' => $item['author']['username'] ?? 'Unknown',
                        'labels' => json_encode($item['labels'] ?? []),
                        'opened_at' => \Carbon\Carbon::parse($item['created_at']),
                        'last_updated_at' => \Carbon\Carbon::parse($item['updated_at']),
                    ]
                );
            }
        }

        // Fetch MRs
        $mrsResponse = Http::withToken($user->gitlab_token)
            ->get("https://gitlab.com/api/v4/projects/{$encodedRepoName}/merge_requests", [
                'per_page' => 100,
            ]);

        if ($mrsResponse->successful()) {
            foreach ($mrsResponse->json() as $item) {
                CachedMergeRequest::updateOrCreate(
                    [
                        'extension_id' => $extension->id,
                        'mr_iid' => $item['iid'],
                    ],
                    [
                        'platform' => 'gitlab',
                        'title' => $item['title'],
                        'description' => $item['description'] ?? '',
                        'state' => $item['state'],
                        'author' => $item['author']['username'] ?? 'Unknown',
                        'labels' => json_encode($item['labels'] ?? []),
                        'opened_at' => \Carbon\Carbon::parse($item['created_at']),
                        'last_updated_at' => \Carbon\Carbon::parse($item['updated_at']),
                    ]
                );
            }
        }

        $this->info("Synced GitLab repository {$extension->repo_full_name}");
    }
}
