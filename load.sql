delete from movie;
delete from movie_genres;
delete from user;
delete from friends;
delete from club;
delete from review;
delete from poll;
delete from poll_about;
delete from poll_option;
delete from club_topics;
delete from member_of;
-- delete from manages;


insert into movie values ('0', 'Porco Rosso', '1992', '01:34:00', 'In 1930s Italy, a veteran World War I pilot is cursed to look like an anthropomorphic pig', './posters/0.jpg');
insert into movie values ('1', 'Princess Mononoke', '1997', '02:13:00', 'While seeking to cure himself of a curse, young warrior Ashitaka stumbles into a conflict between the people of Iron Town and Princess Mononoke, a girl raised by wolves, who will stop at nothing to prevent the destruction of her home.', './posters/1.jpg');
insert into movie values ('2', 'The Wind Rises', '2013', '02:06:00', 'Jiro Horikoshi studies assiduously to fulfill his aim of becoming an aeronautical engineer. As WWII begins, fighter aircraft designed by him end up getting used by the Japanese Empire against its foes.', './posters/2.jpg');

insert into movie values ('3', 'Dune: Part One', '2021-09-10', '02:35:00', 'Paul Atreides arrives on Arrakis after his father accepts the stewardship of the dangerous planet. However, chaos ensues after a betrayal as forces clash to control melange, a precious resource.', './posters/3.jpg');





insert into movie_genres values ('0', 'comedy');
insert into movie_genres values ('1', 'drama');
insert into movie_genres values ('2', 'tragedy');
insert into movie_genres values ('2', 'romance');

insert into user values ('2', 'Michael', 'password_tmp', 'john.doe@example.com', '2025-03-26', '0');
insert into user values ('1', 'Not Michael', 'password_tmp', 'john.doe@example.com', '2025-03-27', NULL);
insert into user values ('0', 'root', '$2y$10$iXMUSlYquk/cDhIQja0uQecNOX6iYNa5.T3nrmhSRMDk3CEVEDWze', 'john.doe@example.com', '2025-03-27', NULL);  -- this password is 'password'
insert into user values ('3', 'Nobodys Friend', 'password_tmp', 'john.doe@example.com', '2025-03-26', '0');



insert into friends values ('0', '1', '2025-03-27');
insert into friends values ('2', '0', '2025-03-29');


insert into club values ('Ghibli Fans', 'In this club we talk about all things made by Studio Ghibli!');

insert into club_topics values ('Ghibli Fans', 'animation');
insert into club_topics values ('Ghibli Fans', 'anime');
insert into club_topics values ('Ghibli Fans', 'ghibli');

insert into member_of values ('Ghibli Fans', '0', 'own');

insert into review values ('0', '0', '2025-03-27 14:00:00', 10, 'My favorite movie!', 'Ghibli Fans', 10, 0);
insert into review values ('0', '0', '2025-03-27 17:30:00', 10, 'I can''t stop watching it it''s so good', NULL, 0, 0);
insert into review values ('1', '0', '2025-03-27 17:30:00', 2, 'I didn''t really like this movie...', NULL, 0, 0);
insert into review values ('3', '0', '2025-03-27 14:00:00', 5, 'This movie was fine', NULL, 10, 0);
insert into review values ('2', '0', '2025-03-27 17:30:00', 10, 'I can''t stop watching it it''s so good', NULL, 0, 0);



insert into poll values ('0', '2025-03-27 14:00:00', 'Which of these movies do you like the best?', 'Ghibli Fans', 0, 0);
insert into poll_option values ('0', '2025-03-27 14:00:00', 'Porco Rosso', 12);
insert into poll_option values ('0', '2025-03-27 14:00:00', 'Princess Mononoke', 6);
insert into poll_about values ('0', '2025-03-27 14:00:00', '0');
insert into poll_about values ('0', '2025-03-27 14:00:00', '1');


insert into poll values ('0', '2025-03-27 16:00:00', 'Did you like how The Wind Rises ended?', 'Ghibli Fans', 0, 0);
insert into poll_option values ('0', '2025-03-27 14:00:00', 'Yes', 12);
insert into poll_option values ('0', '2025-03-27 14:00:00', 'No', 6);
insert into poll_about values ('0', '2025-03-27 14:00:00', '2');



