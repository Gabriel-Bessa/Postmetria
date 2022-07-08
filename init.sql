START TRANSACTION;

CREATE DATABASE IF NOT EXISTS postmetria;

use postmetria;

CREATE TABLE IF NOT EXISTS tb_article_source
(

    id         int auto_increment,
    constraint table_name_pk primary key (id),
    externalId varchar(255) null,
    name       varchar(255) not null


    );

CREATE TABLE IF NOT EXISTS tb_article
(
    id         int auto_increment,
    constraint table_name_pk primary key (id),
    author varchar(255) null,
    title varchar(255) not null,
    description text not null,
    url varchar(255) not null,
    urlToImage varchar(1000) null,
    publishedAt datetime not null null,
    content text not null,
    source_id int null,
    FOREIGN KEY (source_id) REFERENCES tb_article_source(id)
    );
COMMIT;