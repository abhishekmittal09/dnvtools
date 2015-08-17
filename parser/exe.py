

# Import the os module, for the os.walk function
import os
import json
import re
import xml.etree.ElementTree as ET
from StringIO import StringIO


def recursePom(directoryName):

	global totalPoms

	recursePomInfo = []


	        	# print pomExtractedInfo

	return recursePomInfo

def checkPomfileExistence(fname):
	return os.path.isfile(fname) 

#DIR_LOC = raw_input('Please enter the path of the autorelease folder: ')
DIR_LOC = '/var/www/html/gsoc/autorelease'

for module in dependencies['modules']:

	moduleDir = DIR_LOC+'/'+module
	
	if checkPomfileExistence(moduleDir+"/epom.xml") :
		pass
	else :
		continue

	os.system("mvn help:effective-pom -Doutput=epom.xml")
	rootPomFile = "epom.xml"

	actualModules.append(module)

	for dirName, subdirList, fileList in os.walk(directoryName):

	    if dirName=="src" or dirName=="target" or dirName==directoryName:
	    	continue;

	    for fname in fileList:

	        if fname == 'pom.xml' :

	        	#runcommand;

dependencies['modules'] = actualModules

print totalPoms
