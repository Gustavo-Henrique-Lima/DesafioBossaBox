<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;
use App\Models\Tool;

class ToolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tool1 = Tool::create([
            "title" => "Notion",
            "link" => "https://notion.so",
            "description" => "All in one tool to organize teams and ideas. Write, plan, collaborate, and get organized.",
        ]);

        $tags1 = ["organization", "planning", "collaboration", "writing", "calendar"];
        $this->attachTagsToTool($tool1, $tags1);

        $tool2 = Tool::create([
            "title" => "json-server",
            "link" => "https://github.com/typicode/json-server",
            "description" => "Fake REST API based on a json schema. Useful for mocking and creating APIs for front-end devs to consume in coding challenges.",
        ]);

        $tags2 = ["api", "json", "schema", "node", "github", "rest"];
        $this->attachTagsToTool($tool2, $tags2);

        $tool3 = Tool::create([
            "title" => "fastify",
            "link" => "https://www.fastify.io/",
            "description" => "Extremely fast and simple, low-overhead web framework for NodeJS. Supports HTTP2.",
        ]);

        $tags3 = ["web", "framework", "node", "http2", "https", "localhost"];
        $this->attachTagsToTool($tool3, $tags3);
    }

    private function attachTagsToTool(Tool $tool, array $tags): void
    {
        foreach ($tags as $tagName) {
            $tag = Tag::firstOrCreate(["name" => $tagName]);
            $tool->tags()->attach($tag->id);
        }
    }
}
