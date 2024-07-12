CREATE TABLE `commercialdocs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `doc_id` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deadline` date DEFAULT NULL,
  `currency_code` char(3) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `reservation_id` bigint(20) unsigned NOT NULL,
  `quote_confirmed_at` datetime DEFAULT NULL,
  `object_type` enum('trip','circuit','cruise') COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_remarques` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `street` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `street_num` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_code` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `taux_monnaie` (
  `id_taux_monnaie` int(10) NOT NULL AUTO_INCREMENT,
  `nom_monnaie` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `code` char(3) COLLATE latin1_general_ci NOT NULL,
  `taux` float NOT NULL,
  PRIMARY KEY (`id_taux_monnaie`),
  UNIQUE KEY `taux_monnaie_code_unique` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

alter table `commercialdocs`
add constraint `commercialdocs_currency_code_foreign`
foreign key (`currency_code`) references `taux_monnaie` (`code`)
on delete restrict on update cascade;
