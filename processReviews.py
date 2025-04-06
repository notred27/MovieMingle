import csv
import random

usernames = [
    "CineBuff",
    "ReelJunkie",
    "PopcornFiend",
    "FlickFreak",
    "ScreenSaga",
    "FrameFanatic",
    "MovieMuse",
    "SilverReels",
    "PlotTwistr",
    "EpicScenez",
    "FilmNerdz",
    "CriticMode",
    "ReelVibes",
    "SceneSnaps",
    "OscarQuest",
    "WatchLog",
    "MovieScope",
    "FlixCrate",
    "CelluloidZ",
    "StoryFlicks"
]


from datetime import datetime, timedelta

# Define a date range
start_date = datetime.strptime('2025-01-01', '%Y-%m-%d')
end_date = datetime.strptime('2025-05-07', '%Y-%m-%d')

# Generate a random number of days between the start and end dates



def reformat_csv(input_file, output_file):

    i = 0
    with open(input_file, mode='r', newline='', encoding='utf-8') as infile:
        reader = csv.DictReader(infile)  # Read the CSV into a dictionary format

        with open(output_file, 'w') as outfile:

            j = 4
            for name in usernames:
                outfile.write(f"insert into user values ('{j}', '{name}','no_pass', 'dummy@test.com', '2025-03-26', '{int(random.random() * 253)}');\n")
                j+= 1

    
            # Optionally, write a header if you want to add one
            # writer.writerow(['Reformatted Data'])
            
            # Loop through each row in the original CSV
            for row in reader:
                # Reformat the row (example: reformat into custom format)

                # Choose random date
                random_days = random.randint(0, (end_date - start_date).days)
                random_date = start_date + timedelta(days=random_days)


                comment = row['review'].replace("'","''")
                # insert into review values ('0', '0', '2025-03-27 14:00:00', 10, 'My favorite movie!', 'Ghibli Fans', 10, 0);
                tmp = f"insert into review values ('{int(random.random() * 23)}', '{int(random.random() * 253)}', '{random_date.strftime('%Y-%m-%d')} 14:00:00' , '{int(random.random() * 10)}', '{comment}', NULL, 0,0);\n"
                outfile.write(tmp)
                i += 1

                if i == 500:
                    return

# Call the function
input_csv = 'IMDB Dataset.csv'  # Replace with your input file path
output_file = 'reviews.sql'  # Replace with your output file path
reformat_csv(input_csv, output_file)
