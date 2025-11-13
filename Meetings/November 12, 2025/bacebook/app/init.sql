create table users (
    username varchar(100),
    password_hash varchar(256),
    status varchar(1000),
    primary key (username)
);

insert into users values
    ('buford', '$2y$12$ia69t3fwMgtpUQGSILJCPe4th5AHCJj9Ao35S5Y1bhdO92NIpQdCe', 'my name buford'),
    ('ronald', '$2y$12$6MPlwRgrJlzyQj.UrbVKgeolHIdIUXz/nfGjXp5uxdFci28sZUFAi', 'i like trains i like trains i like trains')
;
