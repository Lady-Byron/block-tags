<?php

namespace LadyByron\BlockTags\Access;

use Flarum\User\User;
use Illuminate\Database\Eloquent\Builder;

class ScopePostHiddenTags
{
    public function __invoke(User $actor, Builder $query): void
    {
        if (!$actor || !$actor->id) {
            return;
        }

        $hiddenValue = 'hide';

        $query->where(function (Builder $q) use ($actor, $hiddenValue) {
            $q->whereNotIn('posts.discussion_id', function ($sub) use ($actor, $hiddenValue) {
                $sub->from('discussion_tag')
                    ->join('tag_user', function ($join) use ($actor, $hiddenValue) {
                        $join->on('tag_user.tag_id', '=', 'discussion_tag.tag_id')
                             ->where('tag_user.user_id', '=', $actor->id)
                             ->where('tag_user.subscription', '=', $hiddenValue);
                    })
                    ->select('discussion_tag.discussion_id');
            });
        });
    }
}
