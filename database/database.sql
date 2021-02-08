CREATE TABLE IF NOT EXISTS user(
    username text primary key,
    password text not null
);

CREATE TABLE IF NOT EXISTS color(
    colorId integer primary key,
    name text not null
);

CREATE TABLE IF NOT EXISTS size(
    sizeId integer primary key,
    name text not null
);

CREATE TABLE IF NOT EXISTS gender(
    genderId integer primary key,
    name text not null
);

CREATE TABLE IF NOT EXISTS species(
    speciesId integer primary key,
    name text not null
);

CREATE TABLE IF NOT EXISTS pet (
    petId integer primary key,
    name text,
    speciesId integer not null,
    breed text,
    sizeId integer not null,
    genderId integer not null,
    age integer,
    colorId integer not null,
    description text,
    location text,
    username text not null,
    date text,
    state text DEFAULT 'available', /* adopted or available */
    foreign key (username) references user(username) ON UPDATE CASCADE ON DELETE CASCADE,
    foreign key (speciesId) references species(speciesId),
    foreign key (colorId) references color(colorId),
    foreign key (sizeId) references size(sizeId),
    foreign key (genderId) references gender(genderId)
);

CREATE TABLE IF NOT EXISTS image(
    imageId integer primary key,
    petId integer, 
    alternative text not null,
    foreign key(petId) references pet(petId) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS conversation(
    conversationId integer primary key,
    petId integer, 
    notOwner text, 
    foreign key(petId) references pet(petId) ON DELETE CASCADE,
    foreign key(notOwner) references user(username)  ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS message(
    messageId integer primary key,
    conversationId,
    sender text, 
    receiver text,
    messageText text not null,
    date text, 
    foreign key(sender) references user(username) ON UPDATE CASCADE ON DELETE CASCADE,
    foreign key(receiver) references user(username) ON UPDATE CASCADE ON DELETE CASCADE,
    foreign key(conversationId) references conversation(conversationId) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS proposal(
    proposalId integer primary key,
    petId integer, 
    username text, 
    proposalText text not null,
    date text,
    state text DEFAULT 'waiting', /* waiting, accepted or rejected */
    foreign key(petId) references pet(petId) ON DELETE CASCADE,
    foreign key(username) references user(username) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS favourite(
    petId integer, 
    username text,
    foreign key(petId) references pet(petId) ON DELETE CASCADE,
    foreign key(username) references user(username) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (petId, username)
);


PRAGMA foreign_keys=ON;

/* Triggers - when a proposal is accepted */
CREATE TRIGGER IF NOT EXISTS accept_adoption
   AFTER UPDATE 
   ON proposal
   WHEN NEW.state == 'accepted'
BEGIN
    UPDATE pet
    SET state == 'adopted'
    WHERE petId = NEW.petId;
END;

CREATE TRIGGER IF NOT EXISTS reject_proposals
   AFTER UPDATE 
   ON proposal
   WHEN NEW.state == 'accepted'
BEGIN
    UPDATE proposal
    SET state == 'rejected'
    WHERE petId = NEW.petId AND proposalId != NEW.proposalId;
END;



/* Initial Database */

/* USERS */
INSERT INTO user VALUES(
    'marcelo_sousa',
    '$2y$12$3qMhvo0ljwwGY/TLui88U.3eonmt8vDTZ33ptpTFtm0FRJl8Q21DK'
);

INSERT INTO user VALUES(
    'miram123',
    '$2y$12$QM.EX8KjHeHiYUsi1zq/UOAgC5U3f8lpNdQmIOpGNlPgZPdRw6TGW'
);

INSERT INTO user VALUES(
    'jacinto1999',
    '$2y$12$qUWFZv.sVP03fwq9jaWepuKpppAQl8vMMVFnHbg1HzoCSfQwIr4Pe'
);

INSERT INTO user VALUES(
    'nuria700',
    '$2y$12$vLYZzidJbx.ILgA2ggw9JeZFbPg9jHAa3kOfAtmtPP3qRdM.y88f6'
);

/* COLOR */

INSERT INTO color VALUES(1, 'White');
INSERT INTO color VALUES(2, 'Black');
INSERT INTO color VALUES(3, 'Grey');
INSERT INTO color VALUES(4, 'Light Brown');
INSERT INTO color VALUES(5, 'Dark Brown');
INSERT INTO color VALUES(6, 'Other');


/* SIZE */

INSERT INTO size VALUES(1, 'Small');
INSERT INTO size VALUES(2, 'Medium');
INSERT INTO size VALUES(3, 'Large');

/* GENDER */

INSERT INTO gender VALUES(1, 'Male');
INSERT INTO gender VALUES(2, 'Female');

/* Species */

INSERT INTO species VALUES(1, 'Dog');
INSERT INTO species VALUES(2, 'Cat');
INSERT INTO species VALUES(3, 'Rabbit');
INSERT INTO species VALUES(4, 'Bird');
INSERT INTO species VALUES(5, 'Hamster');
INSERT INTO species VALUES(6, 'Other');


/* PET */

/* pet(petId, name, speciesId, breed, sizeId, genderId, age, colorId, description, location, username, date) */
INSERT INTO pet VALUES( 
    1,              --petId
    'Pantufa',      --name
    1,              --speciesId
    'Hound',        --breed
    1,              --sizeId
    2,              --genderId
    1,              --age
    1,              --colorId
                    --description
    'Pantufa is a sweet houndy girl looking for the perfect place to call her very own! This bashful beauty is seeking a quiet and predictable home with adults only, and another low key and friendly dog to be her buddy. City or apartment living will likely be too much for this sensitive gal, so a home with a yard in a more rural area is ideal.',
    'Porto',        --location   
    'marcelo_sousa',--username
    '2020-11-01',   --date
    'available');   --state

INSERT INTO pet VALUES( 
    2,              --petId
    'Ruger',        --name
    1,              --speciesId
    'Labrador',     --breed
    2,              --sizeId
    1,              --genderId
    6,              --age
    2,              --colorId
                    --description
    'Meet Ruger! This fun loving youngster is eager to get out there and take on the world! Sadly, Ruger was previously an outdoor dog, but he has been working hard on developing his indoor manners. He is a smart pup, and would love to find an adopter who can continue working with him.', 
    'Viseu',        --location
    'miram123',     --username
    '2020-10-21',   --date
    'available');   --state

INSERT INTO pet VALUES( 
    3,                      --petId
    'Calvin',               --name
    2,                      --speciesId
    'Domestic Shorthair',   --breed
    1,                      --sizeId
    2,                      --genderId
    6 ,                     --age
    5,                      --colorId
                            --description
    'Meet Calvin! This handsome guy is eagerly seeking a new home to call his own. He is a social and friendly boy, but can be a little sensitive at times. As such, he would like to find a home with kids over the age of 7. Once he warms up and gets to know you, he is all about pets and attention. ', 
    'Porto',                --location
    'marcelo_sousa',        --username
    '2020-11-02',           --date
    'available');           --state

INSERT INTO pet VALUES( 
    4,              --petId
    'Ginger',       --name
    5,              --speciesId
    'Dwarf',        --breed
    1,              --sizeId
    2,              --genderId
    6,              --age
    6,              --colorId
                    --description
    'Ginger is part of the huge 129 hamster rescue that happened not long ago, where she was housed with multiple hams. This caused her to be wary of her surroundings where she would squeak if suddenly disturbed.', 
    'Coimbra',      --location 
    'miram123',     --username
    '2020-11-02',   --date
    'available');   --state

INSERT INTO pet VALUES(
    5,              --petId
    'Lollipop',     --name
    4,              --speciesId
    'Parrot',       --breed
    1,              --sizeId
    2,              --genderId
    2 ,             --age
    1,              --colorId
                    --description
    'Lollipop craves attention and will often reach a foot out to visitors and volunteers. He loves to take showers and dance to fun music. However, he can display some challenging behaviors and would need to be in a home with an experienced cockatoo person who can continue the positive reinforcement training that he is familiar with.', 
    'Braga',        --location
    'marcelo_sousa',--username
    '2020-11-02',   --date
    'available');   --state

INSERT INTO pet VALUES(
    6,              --petId
    'Snuggles',     --name
    3,              --speciesId
    'Belgian Hare', --breed
    3,              --sizeId
    1,              --genderId
    11,             --age
    3,              --colorId
                    --description
    'Meet Snuggles! This sweet boy is hoping to hop into his furever family! He loves to explore and hop all around his enclosure. He is looking for a home that can be patient and loving while he gets to know you and build a long lasting friendship with his new people!', 
    'Lisboa',       --location
    'marcelo_sousa',--username 
    '2020-11-02',   --date
    'available');   --state

INSERT INTO pet VALUES(
    7,              --petId
    'Frank',        --name
    6,              --speciesId
    'Horse',        --breed
    3,              --sizeId
    1,              --genderId
    14 ,            --age
    2,              --colorId
                    --description
    'Frank is good to catch, lead and groom, he enjoys being pampered and have human interaction. Due to his troubled past, he can be anxious in new environments but once settled, he becomes your best friend.', 
    'Lisboa',       --location
    'nuria700',     --username 
    '2020-11-02',   --date
    'available');   --state

/* Image */
/* image(imageId, petId, alt) */
INSERT INTO image VALUES('1', '1', 'Pantufa imagem 1');
INSERT INTO image VALUES('2', '1', 'Pantufa imagem 2');
INSERT INTO image VALUES('3', '2', 'Ruger imagem 1');
INSERT INTO image VALUES('4', '3', 'image description');
INSERT INTO image VALUES('5', '4', 'image description');
INSERT INTO image VALUES('6', '5', 'image description');
INSERT INTO image VALUES('7', '6', 'image description');
INSERT INTO image VALUES('8', '7', 'image description');
INSERT INTO image VALUES('9', '2', 'Ruger imagem 2');
INSERT INTO image VALUES('10', '2', 'Ruger imagem 3');
INSERT INTO image VALUES('11', '2', 'Ruger imagem 4');

/* Favourite */
/* favourite(idPet,username) */
INSERT INTO favourite VALUES('1', 'miram123'); /*pantufa favorita da nuria */
INSERT INTO favourite VALUES('2', 'marcelo_sousa'); /*lili favorita do marcelo*/

/* Conversations */
/* conversation(conversationId, petId, notOwner) */
INSERT INTO conversation VALUES(1, 1, 'miram123');
INSERT INTO conversation VALUES(2, 2, 'nuria700');
INSERT INTO conversation VALUES(3, 1, 'nuria700');
INSERT INTO conversation VALUES(4, 4, 'jacinto1999');

/* Messages */
/* message(messageId, conversationId, sender, receiver, messageText, date) */
INSERT INTO message VALUES(1, 1, 'miram123', 'marcelo_sousa', 'Hey! Where did you find Pantufa?', '2020-12-02 20:00:00');
INSERT INTO message VALUES(2, 1, 'marcelo_sousa', 'miram123', 'She was abondoned in a park unfortunately.', '2020-12-02 21:00:00');
INSERT INTO message VALUES(3, 1, 'miram123', 'marcelo_sousa', 'Well I think she would love our space! Is she an active dog?', '2020-12-03 21:10:00');

INSERT INTO message VALUES(4, 2, 'nuria700', 'miram123', 'Hello! I would like to know if Ruger behaves around cats. I have two.', '2020-12-04 09:20:00');
INSERT INTO message VALUES(5, 2, 'miram123', 'nuria700', 'Hey Nuria! He does not behave perfectly but I think he would get used to that. He learns fast!', '2020-12-04 10:00:00');

INSERT INTO message VALUES(6, 3, 'nuria700', 'marcelo_sousa', 'Hello Marcelo. I would like to meet Pantufa. Would that be possible?', '2020-12-05 11:05:00');

INSERT INTO message VALUES(7, 4, 'jacinto1999', 'miram123', 'Hi. I never had an hamster but Ginger looks so cute! What would I need to make him feel at home?', '2020-12-05 15:40:00');


/* Proposals */
/* proposal( proposalId, petId, username, proposalText, date, state)*/
INSERT INTO proposal VALUES(1, 1, 'miram123', 'Hey, I would like to adopt this pet! How should I proceed?', '2020-12-06 08:57:08', 'waiting');
INSERT INTO proposal VALUES(2, 1, 'nuria700', 'Hello Marcelo! I would like to adopt Pantufa. I think I have just the right conditons for her.', '2020-12-06 09:20:32', 'waiting');
INSERT INTO proposal VALUES(3, 7, 'marcelo_sousa', 'Hey! I would like to adopt Frank! I have a huge farm and some other horses. He would be at home here.', '2020-12-05 10:25:40', 'waiting');
