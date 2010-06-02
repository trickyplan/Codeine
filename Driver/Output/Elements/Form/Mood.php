<?php

function F_Mood_Render($Args)
{      
    // <select id="prop_current_moodid" class="select" name="prop_current_moodid" tabindex="18" onchange="mood_preview()" => "
    $Moods = array('No', 'Aggravated','Angry','Annoyed','Anxious','Bored','Confused',
            'Crappy','Cranky','Depressed','Discontent','Energetic','Enraged',
            'Enthralled','Exhausted','Happy','High','Horny','Hungry','Infuriated',
            'Irate','Jubilant','Lonely','Moody','Pissed off','Sad','Satisfied',
            'Sore','Stressed','Thirsty','Thoughtful','Tired','Touched','Lazy',
            'Drunk','Ditzy','Mischievous','Morose','Gloomy','Melancholy','Drained',
            'Excited','Relieved','Hopeful','Amused','Determined','Scared','Frustrated',
            'Indescribable','Sleepy','Groggy','Hyper','Relaxed','Restless','Disappointed',
            'Curious','Mellow','Peaceful','Bouncy','Nostalgic','Okay','Rejuvenated',
            'Complacent','Content','Indifferent','Silly','Flirty','Calm','Refreshed',
            'Optimistic','Pessimistic','Giggly','Pensive','Uncomfortable','Lethargic',
            'Listless','Recumbent','Exanimate','Embarrassed','Envious','Sympathetic',
            'Sick','Hot','Cold','Worried','Loved','Awake','Working','Productive',
            'Accomplished','Busy','Blah','Full','Grumpy','Weird','Nauseated',
            'Ecstatic','Chipper','Rushed','Contemplative','Nerdy','Geeky',
            'Cynical','Quixotic','Crazy','Creative','Artistic','Pleased','Bitchy',
            'Guilty','Irritated','Blank','Apathetic','Dorky','Impressed','Naughty',
            'Predatory','Dirty','Giddy','Surprised','Shocked','Rejected','Numb',
            'Cheerful','Good','Distressed','Intimidated','Crushed','Devious','Thankful',
            'Grateful','Jealous','Nervous'); // LJ Standart

    $Output = '<select name="'.$Args['name'].'">';
    foreach ($Moods as $Mood)
        $Output .= '<option value="'.$Mood.'"> <l>Mood:'.$Mood.'</l></option>';
    // style=\'background-image: url("/Images/Icons/Mood/'.$Mood.'.png");\'
    $Output .= '</select>';
    return $Output;
}