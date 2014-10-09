<?php

namespace VoIP\UI\BasicBundle\Extra;

class Configuration {
	private $reference = '';
	private $host = 'pbx.fortyeight.co';
	private $data = array();
	private $url = '';
	private $ip;
	private $phoneName;
	private $defaultUser;
	private $secret;
	function __construct($ip, $phone) {
		$this->reference = $phone->getType();
		$this->ip = $ip;
		$this->phoneName = $phone->getAlias();
		$this->defaultUser = $phone->getDefaultuser();
		$this->secret = $phone->getSecret();
		$this->getConf();
	}
	public function getData()
	{
		return $this->data;
	}
	public function getURL()
	{
		return $this->url;
	}
	public function getConf()
	{
		$host = $this->host;
		$ip = $this->ip;
		$reference = $this->reference;
		$defaultUser = $this->defaultUser;
		$secret = $this->secret;
		$phoneName = $this->phoneName;
		switch ($reference) {
			case 'cisco.spa303':
				$this->url = 'http://'.$ip.'/admin/bcisco.csc';
				$this->data = array(
					'30895' => '1',
					'P30959' => '',
					'63917' => 'SIP',
					'29743' => 'DHCP',
					'29935' => '',
					'29871' => '',
					'30063' => '',
					'30703' => '',
					'30639' => '',
					'30191' => '',
					'30127' => '',
					'31279' => 'Parallel',
					'31471' => '',
					'31407' => '',
					'31599' => '0',
					'12975' => '',
					'13167' => '',
					'13103' => '',
					'13295' => '',
					'12527' => '16384',
					'12463' => '16538',
					'14127' => '101',
					'14319' => '98',
					'14255' => '97',
					'13423' => '96',
					'13359' => '99',
					'9199' => 'none',
					'6127' => '1800',
					'6063' => '30',
					'7855' => '*69',
					'8047' => '*98',
					'559' => '*66',
					'751' => '*86',
					'7983' => '*72',
					'8175' => '*73',
					'8111' => '*90',
					'7279' => '*91',
					'7215' => '*92',
					'7407' => '*93',
					'687' => '*56',
					'879' => '*57',
					'815' => '*71',
					'1007' => '*70',
					'943' => '*67',
					'111' => '*68',
					'47' => '*81',
					'239' => '*82',
					'175' => '*77',
					'367' => '*87',
					'303' => '*78',
					'495' => '*79',
					'2287' => '',
					'2095' => '',
					'2159' => 'GMT-08:00',
					'3823' => '',
					'61743' => '1',
					'1005' => $phoneName,
					'57645' => $phoneName,
					'1325' => '',
					'4077' => '',
					'3437' => '',
					'63277' => 'Default',
					'3501' => 'None',
					'57709' => 'Auto',
					'63469' => '0',
					'63405' => '300',
					'62573' => 'Background Picture',
					'32685' => '1',
					'31853' => '$USER',
					'32109' => '1',
					'32045' => '$USER',
					'25133' => '1',
					'25325' => '$USER',
					'62253' => 'Vertical First',
					'62445' => 'Per Line',
					'62381' => '2',
					'61549' => 'Scrollable',
					'109' => 'n=Classic-1;w=3;c=1',
					'45' => 'n=Classic-2;w=3;c=2',
					'237' => 'n=Classic-3;w=3;c=3',
					'173' => 'n=Classic-4;w=3;c=4',
					'365' => 'n=Simple-1;w=2;c=1',
					'301' => 'n=Simple-2;w=2;c=2',
					'493' => 'n=Simple-3;w=2;c=3',
					'429' => 'n=Simple-4;w=2;c=4',
					'1645' => 'n=Simple-5;w=2;c=5',
					'1581' => 'n=Office;w=4;c=1',
					'1773' => 'n=Pulse;w=5;c=1',
					'1709' => 'n=Du-dut;w=6;c=1',
					'4013' => '0',
					'3181' => '0',
					'3117' => '0',
					'62701' => '0',
					'62637' => '0',
					'62829' => '0',
					'65197' => '',
					'64557' => '',
					'64621' => 'Enterprise',
					'65517' => '',
					'58157' => '',
					'58349' => '',
					'58285' => '',
					'57453' => '',
					'57389' => '',
					'P57581' => '',
					'63215' => '1',
					'61231' => '0',
					'58159' => '0',
					'64495' => '0',
					'63151' => '5060',
					'64367' => 'none',
					'59311' => '0',
					'59183' => '1',
					'59631' => '',
					'61359' => '0',
					'60463' => '1',
					'60655' => '0',
					'63343' => $host,
					'63407' => '1',
					'62959' => '0',
					'62575' => '3600',
					'62895' => '0',
					'62511' => $defaultUser,
					'62703' => $defaultUser,
					'P62639' => $secret,
					'62831' => '0',
					'62767' => '',
					'63727' => 'G711u',
					'63855' => '0',
					'64879' => 'Unspecified',
					'64815' => 'Unspecified',
					'63791' => '0',
					'63663' => 'Auto',
					'55087' => '0',
					'52335' => '0',
					'49263' => '0',
					'55343' => '0',
					'55279' => '5061',
					'56239' => 'none',
					'50415' => '0',
					'50287' => '1',
					'51503' => '',
					'52463' => '0',
					'52591' => '1',
					'52527' => '0',
					'55215' => '',
					'54511' => '1',
					'55855' => '0',
					'54447' => '3600',
					'56047' => '0',
					'54639' => '',
					'54575' => '',
					'P54767' => '',
					'54703' => '0',
					'55919' => '',
					'55599' => 'G711u',
					'55727' => '0',
					'56751' => 'Unspecified',
					'49775' => 'Unspecified',
					'56943' => '0',
					'55791' => 'Auto',
					'46191' => '0',
					'44207' => '0',
					'41135' => '0',
					'47471' => '0',
					'46127' => '5062',
					'47343' => 'none',
					'42287' => '0',
					'42159' => '1',
					'44655' => '',
					'44335' => '0',
					'44463' => '1',
					'37487' => '0',
					'46383' => '1',
					'47983' => '0',
					'46575' => '3600',
					'47919' => '0',
					'46511' => '',
					'47727' => '',
					'P47663' => '',
					'47855' => '0',
					'47791' => '',
					'48751' => 'G711u',
					'48879' => '0',
					'41711' => 'Unspecified',
					'41647' => 'Unspecified',
					'48815' => '0',
					'48687' => 'Auto',
					'38063' => '1',
					'36335' => '0',
					'33263' => '0',
					'39343' => '0',
					'38255' => '5063',
					'39215' => 'none',
					'35439' => '0',
					'34287' => '1',
					'36527' => '',
					'29294' => '0',
					'29422' => '1',
					'29358' => '0',
					'39535' => '1',
					'39855' => '0',
					'39471' => '3600',
					'39023' => '0',
					'39663' => '',
					'39599' => '',
					'P39791' => '',
					'39727' => '0',
					'39919' => '',
					'40623' => 'G711u',
					'40751' => '0',
					'33583' => 'Unspecified',
					'33775' => 'Unspecified',
					'40943' => '0',
					'40815' => 'Auto',
					'30190' => '1',
					'21038' => '0',
					'26158' => '0',
					'32494' => '0',
					'30126' => '5064',
					'32366' => 'none',
					'27310' => '0',
					'27182' => '1',
					'28654' => '',
					'21166' => '0',
					'21294' => '1',
					'21486' => '0',
					'31406' => '1',
					'30958' => '0',
					'31598' => '3600',
					'30894' => '0',
					'31534' => '',
					'31726' => '',
					'P31662' => '',
					'30830' => '0',
					'30766' => '',
					'32750' => 'G711u',
					'31854' => '0',
					'24686' => 'Unspecified',
					'24622' => 'Unspecified',
					'31790' => '0',
					'32686' => 'Auto',
					'23086' => '1',
					'13166' => '0',
					'18286' => '0',
					'24366' => '0',
					'23278' => '5065',
					'24238' => 'none',
					'19438' => '0',
					'19310' => '1',
					'19502' => '',
					'13294' => '0',
					'12398' => '1',
					'12334' => '0',
					'23534' => '1',
					'22830' => '0',
					'23470' => '3600',
					'23022' => '0',
					'22638' => '',
					'22574' => '',
					'P22766' => '',
					'22702' => '0',
					'22894' => '',
					'23598' => 'G711u',
					'23726' => '0',
					'16558' => 'Unspecified',
					'16750' => 'Unspecified',
					'23918' => '0',
					'23790' => 'Auto',
					'15214' => '1',
					'5038' => '0',
					'10158' => '0',
					'15470' => '0',
					'15150' => '5066',
					'16366' => 'none',
					'10286' => '0',
					'11182' => '1',
					'11630' => '',
					'4142' => '0',
					'4270' => '1',
					'4462' => '0',
					'14382' => '1',
					'15982' => '0',
					'14574' => '3600',
					'15918' => '0',
					'14510' => '',
					'14702' => '',
					'P14638' => '',
					'14830' => '0',
					'14766' => '',
					'15726' => 'G711u',
					'15854' => '0',
					'8686' => 'Unspecified',
					'8622' => 'Unspecified',
					'15790' => '0',
					'15662' => 'Auto',
					'7086' => '1',
					'61678' => '0',
					'1262' => '0',
					'7342' => '0',
					'6254' => '5067',
					'7214' => 'none',
					'2414' => '0',
					'2286' => '1',
					'3502' => '',
					'61806' => '0',
					'61934' => '1',
					'61870' => '0',
					'6510' => '1',
					'7854' => '0',
					'6446' => '3600',
					'8046' => '0',
					'6638' => '',
					'6574' => '',
					'P7790' => '',
					'7726' => '0',
					'7918' => '',
					'7598' => 'G711u',
					'558' => '0',
					'1582' => 'Unspecified',
					'1774' => 'Unspecified',
					'750' => '0',
					'622' => 'Auto',
					'63726' => '1',
					'53550' => '0',
					'58670' => '0',
					'65006' => '0',
					'63662' => '5068',
					'64878' => 'none',
					'59822' => '0',
					'59694' => '1',
					'53998' => '',
					'53678' => '0',
					'54830' => '1',
					'55022' => '0',
					'63918' => '1',
					'65518' => '0',
					'65134' => '3600',
					'65454' => '0',
					'65070' => '',
					'65262' => '',
					'P65198' => '',
					'65390' => '0',
					'65326' => '',
					'58094' => 'G711u',
					'58222' => '0',
					'59246' => 'Unspecified',
					'59182' => 'Unspecified',
					'58158' => '0',
					'58030' => 'Auto',
					'55598' => '1',
					'46702' => '0',
					'51822' => '0',
					'49710' => '0',
					'55790' => '5069',
					'56750' => 'none',
					'52974' => '0',
					'52846' => '1',
					'45870' => '',
					'46830' => '0',
					'46958' => '1',
					'46894' => '0',
					'57070' => '1',
					'56366' => '0',
					'57006' => '3600',
					'56558' => '0',
					'57198' => '',
					'57134' => '',
					'P57326' => '',
					'57262' => '0',
					'56430' => '',
					'49966' => 'G711u',
					'50094' => '0',
					'51118' => 'Unspecified',
					'50286' => 'Unspecified',
					'50158' => 'Auto',
					'48750' => '1',
					'38574' => '0',
					'43694' => '0',
					'41838' => '0',
					'48686' => '5070',
					'41710' => 'none',
					'44846' => '0',
					'44718' => '1',
					'36974' => '',
					'38702' => '0',
					'38830' => '1',
					'37998' => '0',
					'48942' => '1',
					'48494' => '0',
					'49134' => '3600',
					'48430' => '0',
					'49070' => '',
					'48238' => '',
					'P48174' => '',
					'48366' => '0',
					'48302' => '',
					'41070' => 'G711u',
					'41198' => '0',
					'42222' => 'Unspecified',
					'42158' => 'Unspecified',
					'41134' => '0',
					'41006' => 'Auto',
					'40622' => '1',
					'30701' => '0',
					'35822' => '0',
					'33710' => '0',
					'40814' => '5071',
					'33582' => 'none',
					'35950' => '0',
					'36846' => '1',
					'28845' => '',
					'29805' => '0',
					'29933' => '1',
					'29869' => '0',
					'40046' => '1',
					'40366' => '0',
					'39982' => '3600',
					'33390' => '0',
					'40174' => '',
					'40110' => '',
					'P40302' => '',
					'40238' => '0',
					'40430' => '',
					'32942' => 'G711u',
					'33070' => '0',
					'34094' => 'Unspecified',
					'34286' => 'Unspecified',
					'33262' => '0',
					'33134' => 'Auto',
					'54509' => '1',
					'55853' => '',
					'56045' => '',
					'55981' => '',
					'56173' => '20',
					'59629' => '',
					'59565' => '',
					'59757' => '',
					'59693' => '',
					'59885' => '',
					'59821' => '',
					'61037' => '',
					'60973' => '',
					'53805' => '1',
					'53997' => '0',
					'53933' => '0',
					'54125' => '0',
					'53741' => '0',
					'53677' => '0',
					'54893' => '1',
					'1389' => 'Speaker',
					'32813' => '12hr',
					'33005' => 'month/day',
					'54957' => 'automatic',
					'55149' => 'source',
					'55085' => 'media',
					'55277' => '1',
					'55213' => '0',
					'54381' => '0',
					'61103' => '1',
					'53231' => '1',
					'44079' => '1',
					'54445' => '1',
					'55405' => '8',
					'55341' => '8',
					'55533' => '10',
					'55469' => '10',
					'35565' => '1',
					'32941' => '8'
				);
				break;
			case 'cisco.spa502g':
				$this->url = 'http://'.$ip.'/admin/bcisco.csc';
				$this->data = array(
					'30895' => '1',
					'P30959' => '',
					'21805' => 'SIP',
					'29743' => 'DHCP',
					'29935' => '',
					'29871' => '',
					'30063' => '',
					'30703' => '',
					'30639' => '',
					'30191' => '',
					'30127' => '',
					'31279' => 'Parallel',
					'31471' => '',
					'31407' => '',
					'31599' => '0',
					'19119' => '',
					'19311' => '',
					'19247' => '',
					'19439' => '',
					'18671' => '16384',
					'18607' => '16538',
					'20335' => '101',
					'20271' => '98',
					'20463' => '97',
					'20399' => '96',
					'19567' => '99',
					'14959' => 'none',
					'10607' => '1800',
					'10543' => '30',
					'4143' => '*69',
					'4335' => '*98',
					'6063' => '*66',
					'5231' => '*86',
					'4271' => '*72',
					'4463' => '*73',
					'4399' => '*90',
					'4591' => '*91',
					'4527' => '*92',
					'5743' => '*93',
					'5167' => '*56',
					'5359' => '*57',
					'5295' => '*71',
					'5487' => '*70',
					'5423' => '*67',
					'5615' => '*68',
					'5551' => '*81',
					'6767' => '*82',
					'6703' => '*77',
					'6895' => '*87',
					'6831' => '*78',
					'7023' => '*79',
					'623' => '',
					'7599' => '',
					'7663' => 'GMT-08:00',
					'111' => '',
					'2735' => '1',
					'32173' => $phoneName,
					'23661' => $phoneName,
					'24941' => '',
					'27437' => '',
					'26797' => '',
					'21165' => 'Default',
					'27117' => 'None',
					'24493' => 'Auto',
					'21357' => '0',
					'21293' => '300',
					'21485' => 'Background Picture',
					'56942' => '1',
					'56878' => '$USER',
					'28653' => '2',
					'25133' => 'n=Classic-1;w=3;c=1',
					'25325' => 'n=Classic-2;w=3;c=2',
					'25261' => 'n=Classic-3;w=3;c=3',
					'25453' => 'n=Classic-4;w=3;c=4',
					'25389' => 'n=Simple-1;w=2;c=1',
					'25581' => 'n=Simple-2;w=2;c=2',
					'25517' => 'n=Simple-3;w=2;c=3',
					'24685' => 'n=Simple-4;w=2;c=4',
					'24621' => 'n=Simple-5;w=2;c=5',
					'24813' => 'n=Office;w=4;c=1',
					'27629' => '0',
					'27565' => '0',
					'26733' => '0',
					'20589' => '0',
					'20525' => '0',
					'20717' => '0',
					'23085' => '',
					'23469' => '',
					'23533' => 'Enterprise',
					'23405' => '',
					'22637' => 'Disabled',
					'24237' => '',
					'24429' => '',
					'24365' => '',
					'24557' => '',
					'2991' => '1',
					'58351' => '0',
					'63471' => '0',
					'3247' => '0',
					'2159' => '5060',
					'3119' => 'none',
					'63599' => '0',
					'64495' => '1',
					'64687' => '',
					'57455' => '0',
					'57583' => '1',
					'2095' => $host,
					'2415' => '1',
					'3759' => '0',
					'2351' => '3600',
					'3951' => '0',
					'2543' => $defaultUser,
					'2479' => $defaultUser,
					'P3695' => $secret,
					'3631' => '0',
					'3823' => '',
					'3503' => 'G711u',
					'61999' => '0',
					'63023' => 'Unspecified',
					'63215' => 'Unspecified',
					'62191' => '0',
					'62063' => 'Auto',
					'44463' => '1',
					'34287' => '0',
					'39407' => '0',
					'38575' => '0',
					'37487' => '5064',
					'38447' => 'none',
					'33391' => '0',
					'40431' => '1',
					'34479' => '',
					'35439' => '0',
					'35567' => '1',
					'37743' => '1',
					'37039' => '0',
					'37679' => '3600',
					'37231' => '0',
					'37871' => '',
					'37807' => '',
					'P36975' => '',
					'36911' => '0',
					'37103' => '',
					'38831' => 'G711u',
					'37935' => '0',
					'38959' => 'Unspecified',
					'39151' => 'Unspecified',
					'38127' => '0',
					'37999' => 'Auto',
					'35631' => '1',
					'25454' => '0',
					'30574' => '0',
					'35887' => '0',
					'35823' => '5065',
					'36783' => 'none',
					'31726' => '0',
					'31598' => '1',
					'31790' => '',
					'25582' => '0',
					'24686' => '1',
					'35055' => '1',
					'36399' => '0',
					'34991' => '3600',
					'36591' => '0',
					'35183' => '',
					'35119' => '',
					'P35311' => '',
					'35247' => '0',
					'36463' => '',
					'36143' => 'G711u',
					'36271' => '0',
					'29102' => 'Unspecified',
					'30318' => 'Unspecified',
					'29294' => '0',
					'36335' => 'Auto',
					'24750' => '1',
					'22766' => '0',
					'27886' => '0',
					'26030' => '0',
					'24942' => '5066',
					'25902' => 'none',
					'20846' => '0',
					'20718' => '1',
					'21934' => '',
					'22894' => '0',
					'23022' => '1',
					'26222' => '1',
					'26542' => '0',
					'26158' => '3600',
					'25710' => '0',
					'26350' => '',
					'26286' => '',
					'P26478' => '',
					'26414' => '0',
					'26606' => '',
					'27310' => 'G711u',
					'27438' => '0',
					'28462' => 'Unspecified',
					'28654' => 'Unspecified',
					'27630' => '0',
					'27502' => 'Auto',
					'24110' => '1',
					'13934' => '0',
					'19054' => '0',
					'17198' => '0',
					'24302' => '5067',
					'17070' => 'none',
					'20206' => '0',
					'20078' => '1',
					'13102' => '',
					'14062' => '0',
					'14190' => '1',
					'24558' => '1',
					'23854' => '0',
					'24494' => '3600',
					'24046' => '0',
					'23662' => '',
					'23598' => '',
					'P23790' => '',
					'23726' => '0',
					'23918' => '',
					'16430' => 'G711u',
					'16558' => '0',
					'17582' => 'Unspecified',
					'17774' => 'Unspecified',
					'16750' => '0',
					'16622' => 'Auto',
					'14254' => '1',
					'12270' => '0',
					'9198' => '0',
					'14510' => '0',
					'13422' => '5068',
					'14382' => 'none',
					'9326' => '0',
					'10222' => '1',
					'10414' => '',
					'11374' => '0',
					'11502' => '1',
					'13678' => '1',
					'15022' => '0',
					'13614' => '3600',
					'15214' => '0',
					'13806' => '',
					'13742' => '',
					'P14958' => '',
					'14894' => '0',
					'15086' => '',
					'14766' => 'G711u',
					'15918' => '0',
					'8750' => 'Unspecified',
					'8942' => 'Unspecified',
					'16110' => '0',
					'15982' => 'Auto',
					'11566' => '1',
					'1390' => '0',
					'6510' => '0',
					'5678' => '0',
					'11758' => '5069',
					'4526' => 'none',
					'7662' => '0',
					'7534' => '1',
					'1582' => '',
					'1518' => '0',
					'2670' => '1',
					'4846' => '1',
					'4142' => '0',
					'4782' => '3600',
					'4334' => '0',
					'4974' => '',
					'4910' => '',
					'P5102' => '',
					'5038' => '0',
					'4206' => '',
					'5934' => 'G711u',
					'6062' => '0',
					'7086' => 'Unspecified',
					'6254' => 'Unspecified',
					'6126' => 'Auto',
					'2734' => '1',
					'58094' => '0',
					'63214' => '0',
					'4014' => '0',
					'2926' => '5070',
					'3886' => 'none',
					'64366' => '0',
					'64238' => '1',
					'65454' => '',
					'58222' => '0',
					'58350' => '1',
					'2158' => '1',
					'2478' => '0',
					'2094' => '3600',
					'3694' => '0',
					'2286' => '',
					'2222' => '',
					'P2414' => '',
					'2350' => '0',
					'2542' => '',
					'3246' => 'G711u',
					'3374' => '0',
					'61742' => 'Unspecified',
					'61934' => 'Unspecified',
					'3566' => '0',
					'3438' => 'Auto',
					'57390' => '1',
					'55406' => '0',
					'60526' => '0',
					'58670' => '0',
					'57582' => '5071',
					'58542' => 'none',
					'53486' => '0',
					'53358' => '1',
					'54574' => '',
					'55534' => '0',
					'55662' => '1',
					'57838' => '1',
					'59182' => '0',
					'57774' => '3600',
					'59374' => '0',
					'58990' => '',
					'58926' => '',
					'P59118' => '',
					'59054' => '0',
					'59246' => '',
					'59950' => 'G711u',
					'60078' => '0',
					'61102' => 'Unspecified',
					'61294' => 'Unspecified',
					'60270' => '0',
					'60142' => 'Auto',
					'12525' => '',
					'12461' => '',
					'12653' => '',
					'12589' => '20',
					'18221' => '',
					'18413' => '',
					'18349' => '',
					'17517' => '',
					'17453' => '',
					'17645' => '',
					'17581' => '',
					'17773' => '',
					'18797' => '1',
					'18733' => '0',
					'18925' => '0',
					'18861' => '0',
					'19501' => '0',
					'19693' => '0',
					'19629' => '1',
					'24749' => 'Speaker',
					'65261' => '12hr',
					'65197' => 'month/day',
					'19949' => 'automatic',
					'19885' => 'source',
					'12909' => 'media',
					'12845' => '1',
					'13037' => '0',
					'12973' => '0',
					'58223' => '1',
					'13869' => '8',
					'14061' => '6',
					'13997' => '12',
					'14189' => '10',
					'57453' => 'Default',
					'65389' => '8',
					'65325' => '10 s'
				);
				break;
			
			default:
				# code...
				break;
		}
	}
}