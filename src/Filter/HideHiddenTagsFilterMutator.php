<?php

namespace LadyByron\BlockTags\Filter;

use Flarum\Filter\FilterState;
use Flarum\Query\QueryCriteria;

class HideHiddenTagsFilterMutator
{
    public function __invoke(FilterState $filterState, QueryCriteria $criteria): void
    {
        $actor = $filterState->getActor();
        if (!$actor || !$actor->id) {
            return;
        }

        $hiddenValue = 'hide';

        $filterState->getQuery()->where(function ($q) use ($actor, $hiddenValue) {
            $q->whereNotExists(function ($sub) use ($actor, $hiddenValue) {
                $sub->selectRaw('1')
                    ->from('discussion_tag')
                    ->join('tag_user', function ($join) use ($actor, $hiddenValue) {
                        $join->on('tag_user.tag_id', '=', 'discussion_tag.tag_id')
                             ->where('tag_user.user_id', '=', $actor->id)
                             ->where('tag_user.subscription', '=', $hiddenValue);
                    })
                    ->whereColumn('discussion_tag.discussion_id', 'discussions.id');
            });
        });
    }
}
