/*==============================================================*/
/* Nom de SGBD :  MySQL 5.0                                     */
/* Date de création :  26/05/2012 16:00:32                      */
/*==============================================================*/


drop table if exists boards;

drop table if exists cards;

drop table if exists cards_decks;

drop table if exists chats;

drop table if exists decks;

drop table if exists earned_points;

drop table if exists games;

drop table if exists hands;

drop table if exists messages;

drop table if exists pick;

drop table if exists plays;

drop table if exists turns;

drop table if exists users;

drop table if exists users_cards_votes;

drop table if exists users_friends;

drop table if exists votes;

/*==============================================================*/
/* Table : boards                                               */
/*==============================================================*/
create table boards
(
   tu_id                int not null,
   ca_id                int not null,
   primary key (tu_id, ca_id)
);

/*==============================================================*/
/* Table : cards                                                */
/*==============================================================*/
create table cards
(
   ca_id                int not null,
   us_id                int not null,
   ca_name              char(255),
   ca_image             char(255),
   primary key (ca_id)
);

/*==============================================================*/
/* Table : cards_decks                                          */
/*==============================================================*/
create table cards_decks
(
   ca_id                int not null,
   de_id                int not null,
   primary key (ca_id, de_id)
);

/*==============================================================*/
/* Table : chats                                                */
/*==============================================================*/
create table chats
(
   us_id                int not null,
   me_id                bigint not null,
   ga_id                int not null,
   primary key (us_id, me_id, ga_id)
);

/*==============================================================*/
/* Table : decks                                                */
/*==============================================================*/
create table decks
(
   de_id                int not null,
   us_id                int not null,
   de_name              char(255) not null,
   de_status            smallint not null,
   primary key (de_id)
);

/*==============================================================*/
/* Table : earned_points                                        */
/*==============================================================*/
create table earned_points
(
   us_id                int not null,
   tu_id                int not null,
   primary key (us_id, tu_id)
);

/*==============================================================*/
/* Table : games                                                */
/*==============================================================*/
create table games
(
   ga_id                int not null,
   de_id                int not null,
   us_id                int not null,
   ga_name              char(255),
   ga_creation_date     datetime,
   ga_password          char(255),
   ga_nb_players        int,
   primary key (ga_id)
);

/*==============================================================*/
/* Table : hands                                                */
/*==============================================================*/
create table hands
(
   tur_tu_id            int not null,
   tu_id                int not null,
   ca_id                int not null,
   us_id                int not null,
   primary key (tur_tu_id, tu_id, ca_id, us_id)
);

/*==============================================================*/
/* Table : messages                                             */
/*==============================================================*/
create table messages
(
   me_id                bigint not null,
   me_text              char(255),
   me_date              datetime,
   primary key (me_id)
);

/*==============================================================*/
/* Table : pick                                                 */
/*==============================================================*/
create table pick
(
   ga_id                int not null,
   ca_id                int not null,
   primary key (ga_id, ca_id)
);

/*==============================================================*/
/* Table : plays                                                */
/*==============================================================*/
create table plays
(
   us_id                int not null,
   ga_id                int not null,
   pl_status            varchar(255),
   primary key (us_id, ga_id)
);

/*==============================================================*/
/* Table : turns                                                */
/*==============================================================*/
create table turns
(
   tu_id                int not null,
   us_id                int not null,
   ga_id                int not null,
   tu_date_start        datetime,
   tu_date_end          datetime,
   di_comments          char(150) not null,
   primary key (tu_id)
);

/*==============================================================*/
/* Table : users                                                */
/*==============================================================*/
create table users
(
   us_id                int not null,
   us_name              char(255),
   us_lastname          char(255),
   us_pseudo            char(255) not null,
   us_password          char(255) not null,
   us_avatar            char(255),
   us_mail              char(255) not null,
   us_birthdate         datetime,
   us_signin_date       datetime,
   us_last_connexion    datetime,
   primary key (us_id)
);

/*==============================================================*/
/* Table : users_cards_votes                                    */
/*==============================================================*/
create table users_cards_votes
(
   us_id                int not null,
   ca_id                int not null,
   primary key (us_id, ca_id)
);

alter table users_cards_votes comment 'Vote pour les cartes ajouté par les joueurs';

/*==============================================================*/
/* Table : users_friends                                        */
/*==============================================================*/
create table users_friends
(
   us_id                int not null,
   use_us_id            int not null,
   fr_date              datetime not null,
   fr_status            int not null,
   primary key (us_id, use_us_id)
);

/*==============================================================*/
/* Table : votes                                                */
/*==============================================================*/
create table votes
(
   us_id                int not null,
   ca_id                int not null,
   tu_id                int not null,
   vo_date              timestamp,
   primary key (us_id, ca_id, tu_id)
);

alter table boards add constraint fk_boards foreign key (tu_id)
      references turns (tu_id) on delete restrict on update restrict;

alter table boards add constraint fk_boards2 foreign key (ca_id)
      references cards (ca_id) on delete restrict on update restrict;

alter table cards add constraint fk_to_create foreign key (us_id)
      references users (us_id) on delete restrict on update restrict;

alter table cards_decks add constraint fk_cards_decks foreign key (ca_id)
      references cards (ca_id) on delete restrict on update restrict;

alter table cards_decks add constraint fk_cards_decks2 foreign key (de_id)
      references decks (de_id) on delete restrict on update restrict;

alter table chats add constraint fk_chats foreign key (us_id)
      references users (us_id) on delete restrict on update restrict;

alter table chats add constraint fk_chats2 foreign key (me_id)
      references messages (me_id) on delete restrict on update restrict;

alter table chats add constraint fk_chats3 foreign key (ga_id)
      references games (ga_id) on delete restrict on update restrict;

alter table decks add constraint fk_decks_users foreign key (us_id)
      references users (us_id) on delete restrict on update restrict;

alter table earned_points add constraint fk_earned_points foreign key (us_id)
      references users (us_id) on delete restrict on update restrict;

alter table earned_points add constraint fk_earned_points2 foreign key (tu_id)
      references turns (tu_id) on delete restrict on update restrict;

alter table games add constraint fk_create foreign key (us_id)
      references users (us_id) on delete restrict on update restrict;

alter table games add constraint fk_decks_games foreign key (de_id)
      references decks (de_id) on delete restrict on update restrict;

alter table hands add constraint fk_hands foreign key (tur_tu_id)
      references turns (tu_id) on delete restrict on update restrict;

alter table hands add constraint fk_hands2 foreign key (tu_id)
      references turns (tu_id) on delete restrict on update restrict;

alter table hands add constraint fk_hands3 foreign key (ca_id)
      references cards (ca_id) on delete restrict on update restrict;

alter table hands add constraint fk_hands4 foreign key (us_id)
      references users (us_id) on delete restrict on update restrict;

alter table pick add constraint fk_pick foreign key (ga_id)
      references games (ga_id) on delete restrict on update restrict;

alter table pick add constraint fk_pick2 foreign key (ca_id)
      references cards (ca_id) on delete restrict on update restrict;

alter table plays add constraint fk_plays foreign key (us_id)
      references users (us_id) on delete restrict on update restrict;

alter table plays add constraint fk_plays2 foreign key (ga_id)
      references games (ga_id) on delete restrict on update restrict;

alter table turns add constraint fk_conduct foreign key (us_id)
      references users (us_id) on delete restrict on update restrict;

alter table turns add constraint fk_games_turns foreign key (ga_id)
      references games (ga_id) on delete restrict on update restrict;

alter table users_cards_votes add constraint fk_users_cards_votes foreign key (us_id)
      references users (us_id) on delete restrict on update restrict;

alter table users_cards_votes add constraint fk_users_cards_votes2 foreign key (ca_id)
      references cards (ca_id) on delete restrict on update restrict;

alter table users_friends add constraint fk_users_friends foreign key (us_id)
      references users (us_id) on delete restrict on update restrict;

alter table users_friends add constraint fk_users_friends2 foreign key (use_us_id)
      references users (us_id) on delete restrict on update restrict;

alter table votes add constraint fk_votes foreign key (us_id)
      references users (us_id) on delete restrict on update restrict;

alter table votes add constraint fk_votes2 foreign key (ca_id)
      references cards (ca_id) on delete restrict on update restrict;

alter table votes add constraint fk_votes3 foreign key (tu_id)
      references turns (tu_id) on delete restrict on update restrict;

