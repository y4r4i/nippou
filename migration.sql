DROP DATABASE app;
CREATE DATABASE app CHARACTER SET utf8mb4 COLLATE utf8mb4_bin;
USE app;

CREATE TABLE role
(
    name         varchar(10)                        not null primary key,
    created_at datetime default CURRENT_TIMESTAMP null,
    updated_at datetime default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP,
    is_active  bool     default true
);

CREATE TABLE role_tree
(
    parent varchar(10) not null,
    child  varchar(10) not null,
    FOREIGN KEY (parent) REFERENCES role (name),
    FOREIGN KEY (child) REFERENCES role (name)
);

CREATE TABLE user
(
    id           varchar(26)                        not null primary key,
    created_at   datetime default CURRENT_TIMESTAMP null,
    updated_at   datetime default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP,
    role_id      varchar(10)                        default null,
    is_active    bool     default true,
    email        varchar(256)                       not null,
    password     varchar(97)                        not null,
    family_name  varchar(30)                        not null,
    given_name   varchar(30)                        not null,
    start_time   varchar(5)                         not null,
    closing_time   varchar(5)                         not null,
    break_time varchar(5)                         not null,
    FOREIGN KEY (role_id) REFERENCES role (name)
);

CREATE TABLE project
(
    id         varchar(26)                        not null primary key,
    created_at datetime default CURRENT_TIMESTAMP null,
    updated_at datetime default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP,
    name       varchar(26)                        not null,
    is_active  bool     default true
);

CREATE TABLE task
(
    id         varchar(26)                        not null primary key,
    created_at datetime default CURRENT_TIMESTAMP null,
    updated_at datetime default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP,
    project_id varchar(26)                        not null,
    name       varchar(26)                        not null,
    is_active  bool     default true,
    jsonschema text                               not null,
    FOREIGN KEY (project_id) REFERENCES project (id)
);

CREATE TABLE assign_role
(
    role_id varchar(10) not null,
    project_id varchar(26) not null,
    FOREIGN KEY (role_id) REFERENCES role (name),
    FOREIGN KEY (project_id) REFERENCES project (id)
);

CREATE TABLE assign_user
(
    user_id varchar(26) not null,
    project_id varchar(26) not null,
    FOREIGN KEY (user_id) REFERENCES user (id),
    FOREIGN KEY (project_id) REFERENCES project (id)
);

CREATE TABLE report
(
    id           varchar(26)                           not null primary key,
    created_at   datetime    default CURRENT_TIMESTAMP null,
    updated_at   datetime    default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP,
    task_id      varchar(26)                           not null,
    submitter_id varchar(26)                           not null,
    approver_id  varchar(26) default null,
    report       text                                  not null,
    FOREIGN KEY (task_id) REFERENCES task (id),
    FOREIGN KEY (submitter_id) REFERENCES user (id),
    FOREIGN KEY (approver_id) REFERENCES user (id)
);

INSERT INTO role (name) VALUES ('Admin');
INSERT INTO role_tree (parent, child) VALUES ('Admin', 'Admin')