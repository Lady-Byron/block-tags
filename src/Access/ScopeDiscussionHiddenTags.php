<?php

namespace LadyByron\BlockTags\Access;

use Flarum\User\User;
use Illuminate\Database\Eloquent\Builder;

class ScopeDiscussionHiddenTags
{
    public function __invoke(User $actor, Builder $query): void
    {
        if (!$actor || !$actor->id) {
            return;
        }

        $hiddenValue = 'hide';

        $query->where(function (Builder $q) use ($actor, $hiddenValue) {
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
