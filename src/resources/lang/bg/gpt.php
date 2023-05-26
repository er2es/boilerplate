<?php

return [
    'tooltip'      => 'Генериране на текст с GPT',
    'title'        => 'Генериране с GPT',
    'confirmtitle' => 'Генериран текст',
    'generation'   => 'Генерирането е в процес, моля, изчакайте ...',
    'error'        => 'Нещо се обърка, моля, опитайте отново',
    'form'         => [
        'topic'    => 'Тема',
        'keywords' => 'Ключови думи',
        'pov'      => [
            'label'         => 'Гледна точка',
            'firstsingular' => 'Ед. Число, 1-во лице (Аз, ме, моя, моето)',
            'firstplural'   => 'Мн. Число, 1-во лице (Ние, нас, наш, нашите)',
            'second'        => 'Второ лице (Ти, твоя, твоите)',
            'third'         => 'Трето лице (Той, тя, то, те)',
        ],
        'length'   => 'Максимален брой думи',
        'tone'     => [
            'label'         => 'Тон',
            'professionnal' => 'Професионален',
            'formal'        => 'Официален',
            'casual'        => 'Неофициален',
            'friendly'      => 'Приятелски',
            'humorous'      => 'Забавен',
        ],
        'language' => 'Език',
        'submit'   => 'Генериране на текст',
        'undo'     => 'Отмени',
        'modify'   => 'Промени',
        'confirm'  => 'Потвърди',
        'type'     => [
            'label'        => 'Тип на текста',
            'tagline'      => 'Слоган',
            'introduction' => 'Въведение',
            'summary'      => 'Резюме',
            'article'      => 'Статия',
        ],
    ],
];
