<?php

namespace App\Http\Controllers;

use App\Models\AboutUsCardSection;
use App\Models\AchievementCounter;
use App\Models\ContactUsInfoCard;
use App\Models\Events;
use App\Models\FooterContent;
use App\Models\HomeCarouselSlider;
use App\Models\HomeVideo;
use App\Models\Conversation;
use App\Models\PrivacyPolicy;
use App\Models\Profile;
use App\Models\SiteSeo;
use App\Models\ProfileCount;
use App\Models\ProfileInterest;
use App\Models\TermsCondition;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\RegistrationMail;
use Mail;
use Auth;
class HomeController extends Controller
{
    public function home()
    {
        
        /* $to = "919126101003";
        $from = "WBMEMB";
        $text = "Your OTP for Westbengal Matrimony is 987656. Please do not share this with anyone. It is valid for 10 minutes.";
        $url = "https://103.229.250.200/smpp/sendsms?to=".$to."&from=".$from."&text=".urlencode($text);
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2FwaS5teXZhbHVlZmlyc3QuY29tL3BzbXMiLCJzdWIiOiJ3Ym1lbWIiLCJleHAiOjE3NTcxNjI1OTN9.7w5cx8uOT13cyrWdbT5y1Y15zmo7gDoeiwkQ57dL0lQ'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        dd($response); */
        $data = AboutUsCardSection::find(1);
        $counter = AchievementCounter::find(1);
        $events = Events::where('status', '1')->latest()->take(3)->get();
        $testimonials = Testimonial::where('status', 1)->latest()->get();
        $homeVideo = HomeVideo::findOrFail(1);
        $hSlider = HomeCarouselSlider::where('status', 1)->orderBy('id', 'asc')->get();
        $siteSeo = SiteSeo::find(1);
        $viewed_profiles =[];
        $profile_view_left =0;
        if (Auth::check() && Auth::user()->user_type == "2")
        {
            $user = Auth::user();
            $viewed_profiles = ProfileCount::where('user_id', $user->id)->where('contact_view_status',  1)->pluck('profile_id')->toArray();
            $count = ProfileCount::where('user_id', $user->id)->where('contact_view_status',  1)->count();
            if(isset($user->current_subscription))
            {
                $profile_view_left = ((int)$user->current_subscription->contact_view-(int)$count);
            }
        }
        $profiles = Profile::where('completed_step', 3)->where('status', 1)->get();
        return view('Frontend.pages.index', compact('data', 'counter', 'events', 'testimonials', 'homeVideo', 'hSlider', 'siteSeo','profiles','viewed_profiles','profile_view_left'));
    }

    public function about()
    {
        $data = AboutUsCardSection::find(1);
        $counter = AchievementCounter::find(1);
        $siteSeo = SiteSeo::find(2);
        return view('Frontend.pages.about', compact('data', 'counter', 'siteSeo'));
    }

    public function profiles(Request $request)
    {
        $user = Auth::user();
        $viewed_profiles =[];
        $profile_view_left =0;
        if (Auth::check() && Auth::user()->user_type == "2")
        {
            $user = Auth::user();
            $viewed_profiles = ProfileCount::where('user_id', $user->id)->where('contact_view_status',  1)->pluck('profile_id')->toArray();
            $count = ProfileCount::where('user_id', $user->id)->where('contact_view_status',  1)->count();
            $viewed_profiles = ProfileCount::where('user_id',$user->id)->where('contact_view_status',  1)->pluck('profile_id')->toArray();
            if(isset($user->current_subscription))
            {
                $profile_view_left = ((int)$user->current_subscription->contact_view-(int)$count);
            }
        }
        $siteSeo = SiteSeo::find(3);
        $query = Profile::where('status', 1)->latest();
        $gender = "All";
        if(isset($request->gender) && $request->gender != "All")
        {
            $gender =$request->gender;
            $query->where('gender', $request->gender);
        }
        $profiles = $query->get();
        return view('Frontend.pages.profiles', compact('siteSeo','profiles','viewed_profiles','profile_view_left','gender'));
    }
    public function profileDetails($slug)
    {
        $user = Auth::user();
        if(isset($user->my_profile) == false)
        {
            $notification = array(
                'message' => 'Please add your profile',
                'alert-type' => 'error'
            );
           
            return redirect()->back()->with($notification);
        }
        else{
            $siteSeo = SiteSeo::find(3);
            $profile = Profile::with('profile_images')->where('slug', $slug)->where('status', 1)->first();
            if($user->id != $profile->user_id)
            {
                if(isset($user->current_subscription) == false || is_null($user->current_subscription))
                {
                    $notification = array(
                        'message' => 'Please purchase subscription',
                        'alert-type' => 'error'
                    );
                
                    return redirect()->route('user.subscriptions.index')->with($notification);
                }
            }

       
        if($profile)
        {
            $interestCount = ProfileInterest::where('user_id', $user->id)->where('status', '!=', 2)->count();
           // dd($user->current_subscription);
            $is_send_interest_available = 0;
            $available_send_interest = 0;
            if(isset($user->current_subscription) && $user->current_subscription->interest> $interestCount)
            {
                $is_send_interest_available = 1;
                 $available_send_interest = $user->current_subscription->interest-$interestCount;
            }
            $count = ProfileCount::where('user_id', $user->id)->where('contact_view_status',  1)->count();
            $received_user_ids = ProfileInterest::where('profile_id', @$user->my_profile->id)->pluck('user_id')->toArray();
            $accepted_user_ids = ProfileInterest::where('profile_id', @$user->my_profile->id)->where('status', 1)->pluck('user_id')->toArray();
           /*  dump($profile->id);
            dd($accepted_user_ids); */
            $received_profile_ids = Profile::whereIn('user_id', @$received_user_ids)->pluck('id')->toArray();
            $accepted_profile_ids = Profile::whereIn('user_id', @$accepted_user_ids)->pluck('id')->toArray();

            $is_send_interest = ProfileCount::where('user_id', $user->id)->where('profile_id', $profile->id)->first();

            $accepted_received_user_ids = ProfileInterest::where('profile_id', @$user->my_profile->id)->where('status', 1)->pluck('user_id')->toArray();
            $accepted_received_profile_ids = Profile::whereIn('user_id', @$received_user_ids)->pluck('id')->toArray();
            $total_send_interest = $interestCount;
           
            $total_send_chat = Conversation::where('sender_id', $user->id)->orWhere('receiver_id', $user->id)->count();
            $is_chated = false;
            $is_contact_view = false;
            $lastConversation = Conversation::where(function($q) use($profile, $user){
                $q->where('sender_id' ,$user->id,)->orWhere('receiver_id', $user->id);
            })->where([
                'profile_id' => $profile->id,
                ])->first();

                if($lastConversation)
                {
                    $is_chated = true; 
                }

            $whatsapp_view = ProfileCount::where('user_id', $user->id)->where('profile_id', $profile->id)->where('contact_view_status',  1)->count();
            if($whatsapp_view)
            {
                $is_contact_view =true;
            }
            $available_send_chat = $user->current_subscription->chat;
            $total_contact_view = $count;
            $available_contact_view = $user->current_subscription->contact_view-$total_contact_view;

            $data['siteSeo'] = $siteSeo;
            $data['profile'] = $profile;
            $data['received_profile_ids'] = $received_profile_ids;
            $data['accepted_received_profile_ids'] = $accepted_received_profile_ids;
            $data['accepted_profile_ids'] = $accepted_profile_ids;
            $data['is_send_interest_available'] = $is_send_interest_available;
            $data['total_send_interest'] = $total_send_interest;
            $data['available_send_interest'] = $available_send_interest;
            $data['total_send_chat'] = $total_send_chat;
            $data['available_send_chat'] = $available_send_chat-$total_send_chat;
            $data['total_contact_view'] = $total_contact_view;
            $data['available_contact_view'] = $available_contact_view;
            $data['is_chated'] = $is_chated;
            $data['is_contact_view'] = $is_contact_view;
/* dump($data['is_contact_view']);
dd($data['available_contact_view']); */
            return view('Frontend.pages.profile_details',$data);

            if($user->id == $profile->user_id)
            {

                return view('Frontend.pages.profile_details', compact('siteSeo','profile','received_profile_ids','accepted_received_profile_ids', 'accepted_profile_ids','is_send_interest_available')); 
            }

            

            if($is_send_interest)
            {
                return view('Frontend.pages.profile_details', compact('siteSeo','profile','received_profile_ids','accepted_received_profile_ids','accepted_profile_ids','is_send_interest_available'));  
            }
            if($user->current_subscription->contact_view > $count)
            {
                /* if($user->id != $profile->user_id)
                {

                    ProfileCount::updateOrCreate([
                        'user_id'=> $user->id,
                        'profile_id'=> $profile->id,
                        'membership_id'=> $user->current_subscription->id
                    ],[
                        'contact_view_status'=> 1,
                        'contact_view_date'=> date('Y-m-d'),
                    ]);
                } */
                return view('Frontend.pages.profile_details', compact('siteSeo','profile','received_profile_ids','accepted_received_profile_ids','accepted_profile_ids','is_send_interest_available'));
            }
            else
            {
                $notification = array(
                    'message' => 'Your subscription limit ('.$user->current_subscription->contact_view.') is over',
                    'alert-type' => 'error'
                );
                return redirect()->route('profiles')->with($notification);
            }
        }
        else
            return redirect()->route('profiles')->with('error','Data not found');
        }
    }

    public function events()
    {
        $data = Events::where('status', '1')->with('event_tag')->latest()->paginate(9);
        $siteSeo = SiteSeo::find(4);
        return view('Frontend.pages.events', compact('data', 'siteSeo'));
    }

    public function events_details($slug, Request $request)
    {
        $data = Events::where('slug', $slug)->with('event_tag')->first();
        $relatedPosts = Events::where('event_tags_id', $data->event_tag->id)
            ->latest()
            ->take(20)
            ->get();
        return view('Frontend.pages.events_details', compact('data', 'relatedPosts'));
    }

    public function contact()
    {
        $siteSeo = SiteSeo::find(5);
        $data = ContactUsInfoCard::find(1);
        return view('Frontend.pages.contact', compact('data', 'siteSeo'));
    }

    public function privacy_policy()
    {
        $siteSeo = SiteSeo::find(6);
        $data = PrivacyPolicy::findOrFail(1);
        return view('Frontend.pages.privacy_policy', compact('data', 'siteSeo'));
    }
    public function refund_policy()
    {
        $siteSeo = SiteSeo::find(6);
        $data = PrivacyPolicy::findOrFail(2);
        return view('Frontend.pages.refund_policy', compact('data', 'siteSeo'));
    }

    public function terms_condition()
    {
        $siteSeo = SiteSeo::find(7);
        $data = TermsCondition::findOrFail(1);
        return view('Frontend.pages.terms_condition', compact('data', 'siteSeo'));
    }
}
