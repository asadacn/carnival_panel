<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute কে গ্রহণ করতে হবে।',
    'active_url' => ':attribute একটি বৈধ URL নয়।',
    'after' => ':attribute :date এর পরের তারিখ হতে হবে।',
    'after_or_equal' => ':attribute :date এর পরের বা সমান তারিখ হতে হবে।',
    'alpha' => ':attribute শুধুমাত্র অক্ষর ধারণ করতে পারে।',
    'alpha_dash' => ':attribute শুধুমাত্র অক্ষর, সংখ্যা, ড্যাশ এবং আন্ডারস্কোর ধারণ করতে পারে।',
    'alpha_num' => ':attribute শুধুমাত্র অক্ষর এবং সংখ্যা ধারণ করতে পারে।',
    'array' => ':attribute একটি অ্যারে হতে হবে।',
    'before' => ':attribute :date এর আগের তারিখ হতে হবে।',
    'before_or_equal' => ':attribute :date এর আগের বা সমান তারিখ হতে হবে।',
    'between' => [
        'numeric' => ':attribute :min এবং :max এর মধ্যে হতে হবে।',
        'file' => ':attribute :min এবং :max কিলোবাইটের মধ্যে হতে হবে।',
        'string' => ':attribute :min এবং :max অক্যালার্সের মধ্যে হতে হবে।',
        'array' => ':attribute :min এবং :max আইটেমের মধ্যে থাকতে হবে।',
    ],
    'boolean' => ':attribute ফিল্ডটি সত্য বা মিথ্যা হতে হবে।',
    'confirmed' => ':attribute নিশ্চিতকরণ মেলে না।',
    'date' => ':attribute একটি বৈধ তারিখ নয়।',
    'date_equals' => ':attribute একটি সমান তারিখ হতে হবে :date।',
    'date_format' => ':attribute ফর্ম্যাট :format এর সাথে মেলে না।',
    'different' => ':attribute এবং :other ভিন্ন হতে হবে।',
    'digits' => ':attribute :digits ডিজিট হতে হবে।',
    'digits_between' => ':attribute :min এবং :max ডিজিটের মধ্যে হতে হবে।',
    'dimensions' => ':attribute-এর অয়্যাবৈধ ছবির মাত্রা আছে।',
    'distinct' => ':attribute ফিল্ডে একটি ডুপ্লিকেট মান আছে।',
    'email' => ':attribute একটি বৈধ ইমেল ঠিকানা হতে হবে।',
    'ends_with' => ':attribute নিম্নর্বলীটির মধ্যে একটিতে শেষ।: :values।',
    'exists' => 'নির্বাচিত :attribute অবৈধ।',
    'file' => ':attribute একটি ফাইল হতে হবে।',
    'filled' => ':attribute ফিল্ডটি একটি মান ধারণ করতে হবে।',
    'gt' => [
        'numeric' => ':attribute :value এর চেয়ে বেশি হতে হবে।',
        'file' => ':attribute :value কিলোবাইটের চেয়ে বেশি হতে হবে।',
        'string' => ':attribute :value অক্যালার্সের চেয়ে বেশি হতে হবে।',
        'array' => ':attribute :value আইটেমের চেয়ে বেশি থাকতে হবে।',
    ],
    'gte' => [
        'numeric' => ':attribute :value এর চেয়ে বেশি বা সমান হতে হবে।',
        'file' => ':attribute :value কিলোবাইটের চেয়ে বেশি বা সমান হতে হবে।',
        'string' => ':attribute :value অক্যালার্সের চেয়ে বেশি বা সমান হতে হবে।',
        'array' => ':attribute কমপক্ষে :value আইটেম থাকতে হবে।',
    ],
    'image' => ':attribute একটি ছবি হতে হবে।',
    'in' => 'নির্বাচিত :attribute অবৈধ।',
    'in_array' => ':attribute ফিল্ডটি :other এ বিদ্যমান নেই।',
    'integer' => ':attribute একটি পূর্ণসংখ্যা হতে হবে।',
    'ip' => ':attribute একটি বৈধ IP ঠিকানা হতে হবে।',
    'ipv4' => ':attribute একটি বৈধ IPv4 ঠিকানা হতে হবে।',
    'ipv6' => ':attribute একটি বৈধ IPv6 ঠিকানা হতে হবে।',
    'json' => ':attribute একটি বৈধ JSON স্ট্রিং হতে হবে।',
    'lt' => [
        'numeric' => ':attribute :value এর চেয়ে কম হতে হবে।',
        'file' => ':attribute :value কিলোবাইটের চেয়ে কম হতে হবে।',
        'string' => ':attribute :value অক্যালার্সের চেয়ে কম হতে হবে।',
        'array' => ':attribute :value আইটেমের চেয়ে কম থাকতে হবে।',
    ],
    'lte' => [
        'numeric' => ':attribute :value এর চেয়ে কম বা সমান হতে হবে।',
        'file' => ':attribute :value কিলোবাইটের চেয়ে কম বা সমান হতে হবে।',
        'string' => ':attribute :value অক্যালার্সের চেয়ে কম বা সমান হতে হবে।',
        'array' => ':attribute অধিক নয় :value আইটেম থাকতে পারে।',
    ],
    'max' => [
        'numeric' => ':attribute :max এর চেয়ে বেশি হতে পারে না।',
        'file' => ':attribute :max কিলোবাইটের চেয়ে বেশি হতে পারে না।',
        'string' => ':attribute :max অক্যালার্সের চেয়ে বেশি হতে পারে না।',
        'array' => ':attribute :max আইটেমের চেয়ে বেশি হতে পারে না।',
    ],
    'mimes' => ':attribute :values ধরনের ফাইল হতে হবে:',
    'mimetypes' => ':attribute :values ধরনের ফাইল হতে হবে:',
    'min' => [
        'numeric' => ':attribute কমপক্ষে :min হতে হবে।',
        'file' => ':attribute কমপক্ষে :min কিলোবাইট হতে হবে।',
        'string' => ':attribute কমপক্ষে :min অক্যালার্স হতে হবে।',
        'array' => ':attribute কমপক্ষে :min আইটেম থাকতে হবে।',
    ],
    'multiple_of' => ':attribute :value এর গুণিতখণ্ড হতে হবে',
    'not_in' => 'নির্বাচিত :attribute অবৈধ।',
    'not_regex' => ':attribute ফর্ম্যাট অবৈধ।',
    'numeric' => ':attribute একটি নম্বর হতে হবে।',
    'password' => 'পাসওয়ার্ড ভুল।',
    'present' => ':attribute ফিল্ডটি উপস্থিত থাকতে হবে।',
    'regex' => ':attribute ফর্ম্যাট অবৈধ।',
    'required' => ':attribute ফিল্ডটি প্রয়োজন।',
    'required_if' => ':attribute ফিল্ডটি প্রয়োজন যখন :other :value।',
    'required_unless' => ':attribute ফিল্ডটি প্রয়োজন যদি না :other :values এ থাকে।',
    'required_with' => ':attribute ফিল্ডটি প্রয়োজন যখন :values উপস্থিত।',
    'required_with_all' => ':attribute ফিল্ডটি প্রয়োজন যখন :values সকলেম উপস্থিত।',
    'required_without' => ':attribute ফিল্ডটি প্রয়োজন যখন :values উপস্থিত নয়।',
    'required_without_all' => ':attribute ফিল্ডটি প্রয়োজন যখন কোনও :values উপস্থিত নয়।',
    'same' => ':attribute এবং :other মেলতে হবে।',
    'size' => [
        'numeric' => ':attribute :size হতে হবে।',
        'file' => ':attribute :size কিলোবাইট হতে হবে।',
        'string' => ':attribute :size অক্যালার্স হতে হবে।',
        'array' => ':attribute :size আইটেম ধারণ করতে হবে।',
    ],
    'starts_with' => ':attribute নিম্নর্বলীটির মধ্যে একটি দিয়ে শুরু হতে হবে: :values।',
    'string' => ':attribute একটি স্ট্রিং হতে হবে।',
    'timezone' => ':attribute একটি বৈধ জোন হতে হবে।',
    'unique' => ':attribute ইতোমধ্যে নেওয়া হয়েছে।',
    'uploaded' => ':attribute আপলোড করতে ব্যর্থ হয়েছে।',
    'url' => ':attribute ফর্ম্যাট অবৈধ।',
    'uuid' => ':attribute একটি বৈধ UUID হতে হবে।',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
