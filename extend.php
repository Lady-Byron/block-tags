<?php

namespace LadyByron\BlockTags;

use Flarum\Discussion\Discussion;
use Flarum\Discussion\Filter\DiscussionFilterer;
use Flarum\Discussion\Search\DiscussionSearcher;
use Flarum\Extend;
use Flarum\Post\Post;

return [
    (new Extend\ModelVisibility(Discussion::class))
        ->scope(\LadyByron\BlockTags\Access\ScopeDiscussionHiddenTags::class, 'view'),

    (new Extend\ModelVisibility(Post::class))
        ->scope(\LadyByron\BlockTags\Access\ScopePostHiddenTags::class, 'view'),

    (new Extend\Filter(DiscussionFilterer::class))
        ->addFilterMutator(\LadyByron\BlockTags\Filter\HideHiddenTagsFilterMutator::class),

    (new Extend\SimpleFlarumSearch(DiscussionSearcher::class))
        ->addSearchMutator(\LadyByron\BlockTags\Search\HideHiddenTagsSearchMutator::class),
];
