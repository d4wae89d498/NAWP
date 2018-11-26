

INSERT INTO `atlas`.`content`
(
`inserted_at`,
`updated_at`,
`title`,
`content`,
`author_id`,
`draft`,
`parent_id`)
VALUES
(
NOW(),
NOW(),
'user',
'',
1,
0,
null);


INSERT INTO `atlas`.`content`
(
`inserted_at`,
`updated_at`,
`title`,
`content`,
`author_id`,
`draft`,
`parent_id`)
VALUES
(
NOW(),
NOW(),
'admin',
'',
1,
0,
null);

INSERT INTO `atlas`.`categorie`
(
`inserted_at`,
`updated_at`,
`title`,
`content`,
`parent_id`,
`author_id`)
VALUES
(
NOW(),
NOW(),
'roles',
'',
null,
1);

INSERT INTO `atlas`.`user`
(`inserted_at`,
`updated_at`,
`birth_place`,
`birth_day`,
`email`,
`first_name`,
`last_name`,
`hashed_password`,
`rgpd`,
`newsletter`,
`role_id`)
VALUES
(NOW(),
now(),
'',
now(),
'faussurier.marc@icloud.com',
'Marc',
'Faussurier',
'',
1,
1,
1);




INSERT INTO `atlas`.`contents_categories`
(
`inserted_at`,
`updated_at`,
`content_id`,
`categorie_id`)
VALUES
(
NOW(),
NOW(),
1,
1
);


INSERT INTO `atlas`.`contents_categories`
(
`inserted_at`,
`updated_at`,
`content_id`,
`categorie_id`)
VALUES
(
NOW(),
NOW(),
2,
1
);
