<?php
function letezik($kapcsolat, $keresztnev,$vezeteknev) {
	$felhasznalok = lekerdezes($kapcsolat,
		'select * from regisztralas where keresztnev AND vezeteknev = :keresztnev',
		[ ':vezeteknev' => $keresztnev,$vezeteknev ]
	);
	return count($felhasznalok) === 1;
}

function regisztral($kapcsolat, $keresztnev,$vezeteknev, $jelszo) {
	$db = vegrehajtas($kapcsolat,
		'insert into regisztralas(keresztnev,vezeteknev, jelszo) 
			values (:keresztnev, :vezeteknev, :jelszo)',
		[
			':keresztnev' 	=> $keresztnev,
			':vezeteknev' => $vezeteknev,
			':jelszo'			=> password_hash($jelszo, PASSWORD_DEFAULT),
		]
	);
	return $db === 1;
}
function ellenoriz($kapcsolat, $keresztnev,$vezeteknev, $jelszo) {
	$felhasznalok = lekerdezes($kapcsolat,
		'select * from regisztralas where keresztnev AND vezeteknev = :keresztnev',
		[ ':vezeteknev' => $keresztnev,$vezeteknev ]
	);
	if (count($felhasznalok) === 1) {
		$felhasznalo = $felhasznalok[0];
		return password_verify($jelszo, $felhasznalo['jelszo']) 
			? $felhasznalo 
			: false;
	}
	return false;
}