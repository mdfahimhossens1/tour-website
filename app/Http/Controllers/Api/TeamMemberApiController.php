<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;

class TeamMemberApiController extends Controller
{
    public function index()
    {
        $members = TeamMember::where('status', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($member) {

                return [

                    'id' => $member->id,

                    'name' => $member->name,

                    'designation_en' => $member->designation_en,
                    'designation_bn' => $member->designation_bn,

                    'image' => $member->image
                        ? asset('storage/' . $member->image)
                        : null,

                    'email' => $member->email,

                    'phone' => $member->phone,

                    'facebook' => $member->facebook,

                    'linkedin' => $member->linkedin,

                    'twitter' => $member->twitter,

                    'bio_en' => $member->bio_en,

                    'bio_bn' => $member->bio_bn,

                ];
            });

        return response()->json([
            'success' => true,
            'data' => $members
        ]);
    }
}