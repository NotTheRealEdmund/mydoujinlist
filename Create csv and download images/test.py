from hentai import Hentai, Format
import csv
import wget
import os

filename = "doujins.csv"
rows = []
doujin_id = 100000

print('Writing to csv file and saving images...')

for x in range(900000):
	try:
	    doujin = Hentai(doujin_id)
	    rows.append([doujin.title(Format.Pretty), doujin.artist[0].name, ', '.join([tag.name for tag in doujin.tag]), 'https://nhentai.net/g/' + str(doujin_id), 'assets/img/' + str(doujin_id) + '.jpg'])
	    image_filename = wget.download(doujin.image_urls[0], out = 'assets/img')
	    os.rename(image_filename, 'assets/img/' + str(doujin_id) + '.jpg')
	except:
	    pass
	doujin_id += 1   
	
with open(filename, 'w') as csvfile:
    csvwriter = csv.writer(csvfile)
    csvwriter.writerows(rows)
    print('\nSuccessfully written all doujins to csv file and saved all images!')

