CREATE TABLE categories(title TEXT, adult INT);
CREATE TABLE questions(question_text TEXT, answer TEXT, value INT, category_id INT, used INT);
CREATE TABLE games(round_id INT, category_id INT, question_id INT);
CREATE TABLE scores(team_name text, score INT);
