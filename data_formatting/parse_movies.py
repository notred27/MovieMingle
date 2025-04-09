import csv

import requests

API_KEY = "b4a5229cef581f1019e47757f39c0d21"



def reformat_csv(input_file, output_file):

    i = 4
    with open(input_file, mode='r', newline='', encoding='utf-8') as infile:
        reader = csv.DictReader(infile)  # Read the CSV into a dictionary format

        with open(output_file, 'w') as outfile:
    
            # Optionally, write a header if you want to add one
            # writer.writerow(['Reformatted Data'])
            
            # Loop through each row in the original CSV
            for row in reader:
                # Reformat the row (example: reformat into custom format)

                runtime = int(row['Runtime'].split(" ")[0])
                hours = runtime // 60
                minutes = runtime % 60

                title = row['Series_Title']

                url = "https://api.themoviedb.org/3/search/movie?query=" + title.replace(" ", "+")
                headers = {
                    "Authorization": "Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiJiNGE1MjI5Y2VmNTgxZjEwMTllNDc3NTdmMzljMGQyMSIsIm5iZiI6MTc0MzYzNDg3MS44NzIsInN1YiI6IjY3ZWRjMWI3NTRmNTk1YmI1NWE3OGI3OSIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.KgKNJ1f02UxzrpqbPPGSdsKie_5KuPKXs2XTuwmFpc4"
                }


                response = requests.get(url, headers=headers)
                data = response.json()

                if data['results'][0]['poster_path'] != None:
                    poster = "https://image.tmdb.org/t/p/w600_and_h900_bestv2/" + data['results'][0]['poster_path']
                else:
                    poster = row['Poster_Link']

                movie_id = data['results'][0]['id']


                url = f"https://api.themoviedb.org/3/movie/{movie_id}"
                response = requests.get(url, headers=headers)
                data = response.json()
                


                formatted_line = f"insert into movie values ('{i}', '{row['Series_Title']}', '{row['Released_Year']}', '{int(hours):02}:{int(minutes):02}:00', '{row['Overview']}', '{poster}');\n"
                
                # Write the formatted line into the new file
                outfile.write(formatted_line)

                for genre in data['genres']:   
                    outfile.write(f"insert into movie_genres values ('{i}', '{genre['name']}'); \n") 


                i += 1
                    

    print(f"Reformatted CSV has been written to {output_file}")

# Call the function
input_csv = 'imdb_top_1000.csv'  # Replace with your input file path
output_file = 'movies.sql'  # Replace with your output file path
reformat_csv(input_csv, output_file)