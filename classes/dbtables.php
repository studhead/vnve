<?php
#
# database tables
#
namespace VNVE;

/**
 * grenzenloos tabel
 */
class Dbtables
{
	const titels = ["name"=>"grenzenloos", "columns"=>"
		`id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        `crdate` datetime NOT NULL,					#creationdate of record
        `nummer` int(5) NOT NULL,		#nummer
        `oudnummer` int(5) NOT NULL,				#oud nummer
        `seizoen` varchar(255) NOT NULL,
        `titel` varchar(512) NOT NULL,
        `auteur` varchar(255) NOT NULL,						#auteur
        `bladzijden` varchar(255) NOT NULL,			#bladzijden
        `artikel` varchar(255),
		PRIMARY KEY (`id`)"]; 
}
?>