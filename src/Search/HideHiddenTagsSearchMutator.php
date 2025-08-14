<?php

namespace LadyByron\BlockTags\Search;

use Flarum\Query\QueryCriteria;
use Flarum\Search\SearchState;

class HideHiddenTagsSearchMutator
{
    public function __invoke(SearchState $searchState, QueryCriteria $criteria): void
    {
        $actor = $searchState->getActor();
        if (!$actor || !$actor->id) {
            return;
        }

        $hiddenValue = 'hide';

        $searchState->getQuery()->where(function ($q) use ($actor, $hiddenValue) {
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
