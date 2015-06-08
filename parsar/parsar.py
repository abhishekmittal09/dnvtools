'''

This python script is used for parsing the various project directories
for pom.xml files and creating a database of module dependancies
Location of the directory is taken as input which is iterated recursively
for pom.xml files which are parsed and dependancy information is updated

@Author : Abhishek Mittal aka Darkdragon
@Email  : abhishekmittaliiit@gmail.com

'''


'''

* Verify if the directory exists, report error on fail

'''

# Import the os module, for the os.walk function
import os
import xml.etree.ElementTree as ET
from StringIO import StringIO
# Set the directory you want to start from


def removeNameSpace(it):
	for _, el in it:
	    if '}' in el.tag:
	        el.tag = el.tag.split('}', 1)[1]  # strip all namespaces
	return it	

'''

Finds the groupID, artifactID and version of all module in the project directory

'''

def parseXML(dirName, filename):
	
	groupID = ''
	artifactID = ''
	version = ''

	xml=''
	with open (dirName+'/'+fname, "r") as xmlFile:
	    xml=xmlFile.read().replace('\n', '')


	it = ET.iterparse(StringIO(xml))

	#removes name space
	it=removeNameSpace(it)
	root = it.root

	if(root.tag == 'project'):
		pass
	else:
		root = root.find('project')

	print dirName+'/'+filename

	for child in root:
		print child.tag
		if child.tag == 'groupId':
			groupID = child.text
		elif child.tag == 'artifactId':
			artifactID = child.text
		elif child.tag == 'version':
			version = child.text
		else:
			pass

	print groupID+':'+artifactID+':'+version

	return



DIR_LOC = raw_input('Please enter the path of the directory to parse for dependancy: ')
DIR_LOC = '/var/www/html/gsoc/l2switch'

for dirName, subdirList, fileList in os.walk(DIR_LOC):
    # print('Found directory: %s' % dirName)
    for fname in fileList:
        if fname == 'pom.xml' :
        	# print dirName+'/'+fname
        	parseXML(dirName, fname)
        	

#gets the directory location as the input from the user

