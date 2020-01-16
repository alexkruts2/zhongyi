<?php

use App\User;


if (!function_exists('user_info')) {
    function user_info($user) {
        $userId = $user instanceof User ? $user->id : $user;
        $data = Cache::get(USER_DATA_CACHE_KEY . $userId);

        $user = $user instanceof User ? $user : User::find($userId);
        $self = user();

        if (is_null($data)) {
            $data = [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => asset_url($user->avatar, 'avatar'),
                'country' => (string)$user->country,
                'phone' => (string)$user->phone,
                'createdAt' => date($user->created_at)
            ];

            Cache::forever(USER_DATA_CACHE_KEY . $user->id, $data);
        }

//        $data['integral'] = $userInfo->integral;
//        $data['upload_count'] = $user->treasures ? $user->treasures->count() : 0;
//        $data['search_count'] = $user->transactions ? $user->transactions()->whereNotNull('start_date')->count() : 0;
//        $data['treasure_count'] = $user->fixedBox()->count() + $user->temporaryBox()->count();
//        $data['reviews'] = $reviews;
//        $data['is_black_user'] = $user->isBlackHistoryUser();
//        $data['is_kicked_user'] = $user->kicked_flag;
//        $data['joined_community_count'] = $user->joined_comunities()->count();
//        $data['created_community_count'] = $user->created_comunities()->where('is_check', true)->count();
//        $data['is_free_cost'] = $user->is_free_cost;
//
//        if (!is_null($self) && $userId == $self->id) {
//            $data['cash'] = $self->cash;
//            $data['max_fixed_limit'] = $self->userInfo->max_fixed_limit;
//            $data['fixed_lock_num'] = $self->userInfo->fixed_lock_num;
//            $data['max_temp_limit'] = $self->userInfo->max_temp_limit;
//            $data['temp_lock_num'] = $self->userInfo->temp_lock_num;
//            $data['aplan_id'] = is_null($self->aplan) ? 0 : $self->aplan->id;
//        } else {
//            $data['is_friend'] = (!is_null($self) && !is_null($self->friends()) && $self->friends()->where('friend_id', $userId)->count() > 0) ? true : false;
//        }

        return $data;
    }
}