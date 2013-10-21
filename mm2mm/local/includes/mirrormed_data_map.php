<?php


	//This file is designed to map 
	//Medical Manager doctor numbers to relevant information about them
	//like which MirrorMed provider_id they are mapped to
	//and which location they were mapped to.


	$eskro = 1;
	$guerrero = 2;
	$herder = 3;
	$hospital = 4;
	$krolikowski = 5;
	$muzljakovich = 6;
	$rodriguez = 7;
	$smith = 8;
	$visser = 9;
	$other = 0;
	$parageis = 0;
	


	$doctor2ids = array(

87	=> $eskro,
88	=> $eskro,	
99	=> $guerrero,
60	=> $guerrero,
72	=> $guerrero,
2	=> $guerrero,
16	=> $guerrero,
25	=> $guerrero,
34	=> $guerrero,
42	=> $guerrero,
44	=> $guerrero,
45	=> $guerrero,
46	=> $guerrero,
49	=> $guerrero,
64	=> $guerrero,
76	=> $guerrero,
78	=> $guerrero,
92	=> $guerrero,
96	=> $guerrero,
82	=> $herder,
83	=> $herder,
18	=> $other,
85	=> $krolikowski,
80	=> $krolikowski,
81	=> $krolikowski,
11	=> $other,
4	=> $muzljakovich,
95	=> $muzljakovich,
74	=> $muzljakovich,
5	=> $muzljakovich,
12	=> $muzljakovich,
35	=> $muzljakovich,
48	=> $muzljakovich,
63	=> $muzljakovich,
79	=> $muzljakovich,
93	=> $muzljakovich,
6	=> $other, // *** OLD DR G
36	=> $other, // *** OLD DR P
55	=> $other, // ***OLD SW REHAB
3	=> $parageis,
0	=> $other, // PHYSICIANS CTR OF PHYSICAL MEDICINE
56	=> $other, //REHAB HOSPITAL
61	=> $rodriguez, 
62	=> $rodriguez, 
84	=> $smith,
86	=> $smith,
89	=> $smith,
1	=> $visser,
10	=> $visser,
13	=> $visser,
15	=> $visser,
33	=> $visser,
41	=> $visser,
43	=> $visser,
47	=> $visser,
65	=> $visser,
75	=> $visser,
77	=> $visser,
91	=> $visser,
94	=> $visser,
98	=> $visser,

);

//var_export($doctor2ids);
/*
battle creek #s
13
15
16
25
5
12
62
*/

	$portage = 1;
	$battlecreek = 2;

	$locations = array(
		$portage => 'portage',
		$battlecreek => 'battlecreek'
	);


	$doctor2ehr = array(

87	=> $portage,
88	=> $portage,	
99	=> $portage,
60	=> $portage,
72	=> $portage,
2	=> $portage,
16	=> $battlecreek,
25	=> $battlecreek,
34	=> $portage,
42	=> $portage,
44	=> $portage,
45	=> $portage,
46	=> $portage,
49	=> $portage,
64	=> $portage,
76	=> $portage,
78	=> $portage,
92	=> $portage,
96	=> $portage,
82	=> $portage,
83	=> $portage,
18	=> $portage,
85	=> $portage,
80	=> $portage,
81	=> $portage,
11	=> $portage,
4	=> $portage,
95	=> $portage,
74	=> $portage,
5	=> $battlecreek,
12	=> $battlecreek,
35	=> $portage,
48	=> $portage,
63	=> $portage,
79	=> $portage,
93	=> $portage,
6	=> $portage, 
36	=> $portage, 
55	=> $portage, 
3	=> $portage,
0	=> $portage, 
56	=> $portage, 
61	=> $portage, 
62	=> $battlecreek, 
84	=> $portage,
86	=> $portage,
89	=> $portage,
1	=> $portage,
10	=> $portage,
13	=> $battlecreek,
15	=> $battlecreek,
33	=> $portage,
41	=> $portage,
43	=> $portage,
47	=> $portage,
65	=> $portage,
75	=> $portage,
77	=> $portage,
91	=> $portage,
94	=> $portage,
98	=> $portage,

);


//var_export($doctor2ehr);
/* Original list
87	ESKRO  OT
88	ESKRO  OT
99	GUERRERO
60	Guerrero  Md
72	Guerrero  MD
2	GUERRERO MD
16	GUERRERO MD
25	GUERRERO MD
34	GUERRERO MD
42	GUERRERO MD
44	GUERRERO MD
45	GUERRERO MD
46	GUERRERO MD
49	GUERRERO MD
64	GUERRERO MD
76	GUERRERO MD
78	GUERRERO MD
92	GUERRERO MD
96	GUERRERO MD
82	HERDER OT
83	HERDER OT
18	HOSPITAL
85	KROLIKOWSKI OT
80	KROLIKOWSKI OT
81	KROLIKOWSKI  OT
11	MEDICAL RECORDS
4	MUZLJAKOVICH
95	MUZLJAKOVICH
74	Muzljakovich MD
5	MUZLJAKOVICH MD
12	MUZLJAKOVICH MD
35	MUZLJAKOVICH MD
48	MUZLJAKOVICH MD
63	MUZLJAKOVICH MD
79	MUZLJAKOVICH MD
93	MUZLJAKOVICH MD
6	*** OLD DR G
36	*** OLD DR P
55	***OLD SW REHAB
3	PAREIGIS MD
0	PHYSICIANS CTR OF PHYSICAL MEDICINE
56	REHAB HOSPITAL
61	RODRIGUEZ PT
62	RODRIGUEZ PT
84	SMITH OT
86	SMITH OT
89	SMITH OT
1	VISSER MD
10	VISSER MD
13	VISSER MD
15	VISSER MD
33	VISSER MD
41	VISSER MD
43	VISSER MD
47	VISSER MD
65	VISSER MD
75	VISSER MD
77	VISSER MD
91	VISSER MD
94	VISSER MD
98	VISSER MD
*/



?>
