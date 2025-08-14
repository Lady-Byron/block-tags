# block-tags
Flarum companion for FoF Follow Tags: hide discussions/posts with user-hidden tags across ALL pages

# Block Tags (Hide Everywhere)

Companion extension for **fof/follow-tags**.

When a user sets a tag to **Hide**, discussions with that tag (and their posts) become invisible to that user **across ALL pages** (All Discussions, tag pages, profile streams, search results, etc.).

## Install (dev/testing)

```bash
# in your Flarum root
composer config repositories.ladybyron-block-tags '{"type":"vcs","url":"https://github.com/Lady-Byron/block-tags"}'
composer require ladybyron/block-tags:dev-main
php flarum cache:clear
