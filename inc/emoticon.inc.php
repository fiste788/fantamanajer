<?php
class Emoticon
{
	public static $emoticon = array(
		array('name'=>'andy','cod'=>':^)','title'=>'Scioccato'),
		array('name'=>'angry','cod'=>':@','title'=>'Arrabbiato'),
		array('name'=>'Baring_teeth_smiley','cod'=>'8o|','title'=>'Incazzato'),
		array('name'=>'beer','cod'=>'(b)','title'=>'Birra'),
		array('name'=>'brheart','cod'=>'(u)','title'=>'Cuore Spezzato'),
		array('name'=>'Cigarette','cod'=>'(ci)','title'=>'Sigaretta'),
		array('name'=>'coffee','cod'=>'(c)','title'=>'Caffè'),
		array('name'=>'computer','cod'=>'(co)','title'=>'Computer'),
		array('name'=>'confused','cod'=>':s','title'=>'Confuso'),
		array('name'=>'cry','cod'=>':\'(','title'=>'Pianto'),
		array('name'=>'devil','cod'=>'(6)','title'=>'Diavolo'),
		array('name'=>'Dont_tell_anyone','cod'=>':-#','title'=>'Bocca chiusa'),
		array('name'=>'drink','cod'=>'(d)','title'=>'Drink'),
		array('name'=>'mail','cod'=>'(e)','title'=>'Mail'),
		array('name'=>'blushing','cod'=>':$','title'=>'Arrossire'),
		array('name'=>'Fingerscrossed','cod'=>'(yn)','title'=>'Dita Incrociate'),
		array('name'=>'Hi_five','cod'=>'(h5)','title'=>'Batti Cinque'),
		array('name'=>'coolglasses','cod'=>'(h)','title'=>'Occhiali da Sole'),
		array('name'=>'lamp','cod'=>'(i)','title'=>'Idea'),
		array('name'=>'grin','cod'=>':D','title'=>'Felice'),
		array('name'=>'emblem-favorite','cod'=>'(l)','title'=>'Cuore'),
		array('name'=>'kiss','cod'=>'(k)','title'=>'Bacio'),
		array('name'=>'unhappy','cod'=>':(','title'=>'Triste'),
		array('name'=>'Sarcastic_smiley','cod'=>'^o)','title'=>'Sarcastico'),
		array('name'=>'sick','cod'=>'+o(','title'=>'Vomito'),
		array('name'=>'sleeping','cod'=>'|-)','title'=>'Addormentato'),
		array('name'=>'smile','cod'=>':)','title'=>'Felice'),
		array('name'=>'tongue','cod'=>':p','title'=>'Linguaccia'),
		array('name'=>'Soccer_ball','cod'=>'(so)','title'=>'Pallone'),
		array('name'=>'Thinking_smiley','cod'=>'*-)','title'=>'Pensieroso'),
		array('name'=>'wink','cod'=>';)','title'=>'Occhilino')
	);
	
	public static function getEmoticons()
	{
		return $emoticons;
	}
	
	public static function replaceEmoticon($text,$path)
	{
		foreach(self::$emoticon as $key => $val)
			$text = str_replace($val['cod'],'<img alt="' . $val['cod'] . '" class="emoticon" height="24" src="' . $path . $val['name'] . '.png" title="' . $val['title'] . '" width="24" />',$text);
		return $text;
	}
}
?>
