#!/usr/bin/env python
"""Usage: ./open_gasolineras.py [-d DIR] [-r DIR] [--quiet | --verbose]

-h --help       show this
-d DIR          specify where json output files are saved [default: .]
-r DIR          specify raw output directory [default: .]
-quiet          do not show any debug traces

"""
from docopt import docopt

from shutil import copy
from urllib2 import urlopen, URLError, HTTPError
import os
import glob
import zipfile
import json
import re
import logging

debug_trace = True
output_dir = '.'
raw_output_dir = '.'


def downloadFile(url):
    # Open the url
    try:
        _f = str(raw_output_dir) + '/zip/' + os.path.basename(url)
        if not os.path.exists(_f):
            f = urlopen(url)
            logging.info('downloading' + url)

            # Open our local file for writing
            with open(_f, 'wb') as local_file:
                local_file.write(f.read())

    # handle errors
    except HTTPError as e:
        print('HTTP Error:', e.code, url)
    except URLError as e:
        print('URL Error:', e.reason, url)


def extract(_file):
    _file = str(raw_output_dir) + '/zip/'+_file+'.zip'
    zip_ref = zipfile.ZipFile(_file, 'r')
    zip_ref.extractall(str(raw_output_dir) + '/csv')
    zip_ref.close()
    # os.remove(_file)


def generateFileName(_csvfile, _file2):
    with open(_csvfile, 'r') as f:
        first_line = f.readline()
    # ; Fichero Generado por el MINETUR 24/11/2013
    matchObj = re.match(r'.*(\d{2})/(\d{2})/(\d{4})', first_line)
    #logging.info(matchObj.group(3))
    #logging.info(matchObj.group(2))
    #logging.info(matchObj.group(1))
    #logging.info(_csvfile)
    
    #leny = len(_csvfile)
    #logging.info(_file2)
    #m2 = matchObj.group(3) + matchObj.group(2) + matchObj.group(1) + '/' + _csvfile[leny:14]     
    m2 = matchObj.group(3) + matchObj.group(2) + matchObj.group(1) + '/' + _file2[5:8]  
    #logging.info(m2)
    return m2 


def convertCsvToJson(_file):
    _file2 = _file
    
    _file = raw_output_dir + '/csv/'+_file+'.csv'
    #logging.info(_file)
    
    #logging.info(_file2)
    
    #_todayFileName2 = generateFileName(_file2)
    #logging.info(_todayFileName2)
    
    _todayFileName = generateFileName(_file, _file2)
    logging.info(_todayFileName)

    # export file to list
    lines = [line.strip() for line in open(_file)]
    i = 0
    jsonStack = []
    geoJsonStack = []

    # loop thrue lines
    for line in lines:
        i += 1
        # ignore 1st 2 lines
        if i < 3 or line == '':
            continue

        _line = line.split(',', 2)
        _json = {
            'lat': float(_line[1]),
            'lng': float(_line[0]),
            'name': '',
            'price': 0,
        }

        # TEXACO Horario Especial 1,009 e
        if _line[2].find('Horario Especial') != -1:
            matchObj = re.match(
                r'(.*) Horario Especial (\d,\d{3}) e',
                _line[2].strip()[1:]
            )
            if matchObj:
                _json['name'] = matchObj.group(1).strip()
                price = matchObj.group(2).replace(',', '.')
                _json['price'] = float(price)
            else:
                logging.info(' [ Not parsed] ' + line)
        # ESTACION DE SERVICIOS EL  L-D: 24H 0,997 e
        # THADER L-D: 24H 1,309 e
        # BTP L: 06:30-22:30 1,308 e
        else:
            matchObj = re.match(
                r'(.*) .*: .* (\d,\d{3}) e',
                _line[2].strip()[1:]
            )
            if matchObj:
                _json['name'] = matchObj.group(1).strip()
                price = matchObj.group(2).replace(',', '.')
                _json['price'] = float(price)
            else:
                logging.info(' [ Not parsed] ' + line)
        jsonStack.append(_json)
        geoJsonStack.append({
            'type': 'Feature',
            'geometry': {
                'type': 'Point',
                'coordinates': [_json['lng'], _json['lat']]
            },
            'properties': {
                'name': _json['name'],
                'price': _json['price']
            }
        })

    writeToJson(jsonStack, _todayFileName)
    writeToGeoJson({
        "type": "FeatureCollection",
        "features": geoJsonStack
    }, _todayFileName)


def writeToGeoJson(object, filename):
    _f = str(output_dir) + '/geojson/' + filename + '.geojson'
    logging.info(' - writing to ' + _f)
    if not os.path.exists( os.path.dirname(_f) ):
        os.makedirs( os.path.dirname(_f) )
    with open(_f, 'w') as outfile:
        json.dump(object, outfile)
    copy(_f, str(output_dir) + '/geojson/latest/')


def writeToJson(object, filename):
    _f = str(output_dir) + '/json/' + filename + '.json'
    logging.info(' - writing to ' + _f)
    if not os.path.exists( os.path.dirname(_f) ):
        os.makedirs( os.path.dirname(_f) )
    with open(_f, 'w') as outfile:
        json.dump(object, outfile)
    copy(_f, str(output_dir) + '/json/latest/')


def extractZipFilenames():
    files = ['GPR', 'G98', 'GOA', 'NGO', 'GOB', 'GOC', 'BIO', 'G95', 'BIE', 'GLP', 'GNC']
    return files


def init():
    if not os.path.exists(str(raw_output_dir) + '/csv'):
        os.makedirs(str(raw_output_dir) + '/csv')
    if not os.path.exists(str(output_dir) + '/json/latest'):
        os.makedirs(str(output_dir) + '/json/latest')
    if not os.path.exists(str(output_dir) + '/geojson/latest'):
        os.makedirs(str(output_dir) + '/geojson/latest')
    if not os.path.exists(str(raw_output_dir) + '/zip'):
        os.makedirs(str(raw_output_dir) + '/zip')


def main(raw_output_dir):
    logging.info('--- initialising')
    
    output_dir = arguments['DIR'][0]
    if len(arguments['DIR']) == 1:
    	raw_output_dir = '.'
    else:
    	raw_output_dir = arguments['DIR'][1]
    	
    logging.info(raw_output_dir)
    init()

    logging.info('\n--- extract zip filenames from html')
    files = extractZipFilenames()

    logging.info('\n--- delete existing zip files')
    for i in glob.glob(str(raw_output_dir) + u'/zip/*.zip'):
        os.unlink (i)

    logging.info('\n--- download zip files')
    for _file in files:
        _file = 'eess_%s' % (_file,)
        url = (
            'http://www6.mityc.es/aplicaciones/carburantes/'
            '%s.zip' % (
                _file,
            )

        )
        downloadFile(url)

    logging.info('\n--- extract zip files')
    for _file in files:
        _file = 'eess_%s' % (_file,)
        extract(_file)

    logging.info('\n--- convert csv to json')
    for _file in files:
        _file = 'eess_%s' % (_file,)
        convertCsvToJson(_file)

if __name__ == '__main__':
    arguments = docopt(__doc__)
    if not arguments['--quiet']:
        logging.basicConfig(level=logging.INFO)
    
    output_dir = arguments['-d']
    raw_output_dir = arguments['-r']
    
    output_dir = arguments['DIR'][0]
    if len(arguments['DIR']) == 1:
    	raw_output_dir = '.'
    else:
    	raw_output_dir = arguments['DIR'][1]
    
    main(raw_output_dir)
