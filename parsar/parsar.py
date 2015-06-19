'''

This python script is used for parsing the various project directories
for pom.xml files and creating a database of module dependencies
Location of the directory is taken as input which is iterated recursively
for pom.xml files which are parsed and dependency information is updated

@Author : Abhishek Mittal aka Darkdragon
@Email  : abhishekmittaliiit@gmail.com

'''


'''

* Verify if the directory exists, report error on fail

'''

# Import the os module, for the os.walk function
import os
import json
import re
import xml.etree.ElementTree as ET
from StringIO import StringIO

'''

removes the namespaces from pom file

'''

def removeNameSpace(it):
	for _, el in it:
	    if '}' in el.tag:
	        el.tag = el.tag.split('}', 1)[1]  # strip all namespaces
	return it	

'''

removes #set from pom file

'''

def removeSetHash(content):

	index = 0
	while content[index] != '<':
		index = index + 1

	return content[index:]

'''

Init XML

'''

def initXML(dirName, filename):
	xml=''
	with open (dirName+'/'+filename, "r") as xmlFile:
	    xml=xmlFile.read().replace('\n', '')

	xml = removeSetHash(xml)

	it = ET.iterparse(StringIO(xml))

	#removes name space
	it=removeNameSpace(it)
	
	return it.root


'''

Returns the dictionary groupId+':'+artifactId+':'+version

'''
def getUniqueId(dirName, filename):

	root = initXML(dirName, filename)

	groupId = ''
	artifactId = ''
	version = ''

	#makes project as the root tag
	if root.tag == "project":
		pass
	else:
		root = root.find('project')

	if root is not None:
		pass
	else :
		return {}

	for child in root:
		if child.tag == 'groupId':
			groupId = child.text
		if child.tag == 'artifactId':
			artifactId = child.text
		if child.tag == 'version':
			version = child.text

	temp = {}
	temp['groupId']=groupId
	temp['artifactId']=artifactId
	temp['version']=version

	return temp



'''

returns the names of the major modules/projects in the pom file
TODO : Check for case sensitive information

'''
def getModuleNames(dirName, filename):

	root = initXML(dirName, filename)

	modules=[]

	#makes modules as the root tag
	if root.tag == "modules":
		pass
	else:
		root = root.find('modules')

	if root is not None:
		pass
	else :
		return modules

	for child in root:
	    if child.tag == "module":
	    	modules.append(child.text)

	return modules

'''

returns the names of the major modules/projects dependency in the pom file
TODO : Check for case sensitive information

'''
def getDependencyNames(dirName, filename):

	root = initXML(dirName, filename)

	dependency=[]

	#makes modules as the root tag
	if root.tag == "dependencies":
		pass
	else:
		root = root.find('dependencies')

	if root is not None:
		pass
	else :
		return dependency

	for child in root:
		if child.tag == "dependency":
			dependencyInfo = {}
			dependencyInfo["groupId"]=""	
			dependencyInfo["artifactId"]=""	
			dependencyInfo["version"]=""	
			dependencyInfo["scope"]=""	
			for subchild in child:
				if subchild.tag == "groupId":
					dependencyInfo["groupId"]=subchild.text

				if subchild.tag == "artifactId":
					dependencyInfo["artifactId"]=subchild.text
				
				if subchild.tag == "version":
					dependencyInfo["version"]=subchild.text

				if subchild.tag == "scope":
					dependencyInfo["scope"]=subchild.text

			dependency.append(dependencyInfo)
	return dependency

'''

returns the names of the major modules/projects parents in the pom file
TODO : Check for case sensitive information

'''
def getParentNames(dirName, filename):
	
	root = initXML(dirName, filename)

	parent = []

	#makes modules as the root tag
	if root.tag == "parent":
		pass
	else:
		root = root.find('parent')

	if root is not None:
		pass
	else :
		return parent

	for child in root:
		parentInfo = {}
		parentInfo["groupId"]=""
		parentInfo["artifactId"]=""
		parentInfo["version"]=""
		if child.tag == 'groupId':
			parentInfo["groupId"] = child.text
		if child.tag == 'artifactId':
			parentInfo["artifactId"] = child.text
		if child.tag == 'version':
			parentInfo["version"] = child.text
	
		parent.append(parentInfo)

	return parent

'''

returns the names of the major modules/projects parents in the pom file
TODO : Check for case sensitive information

'''
def getPomName(dirName, filename):
	
	root = initXML(dirName, filename)

	#makes modules as the root tag
	if root.tag == "name":
		pass
	else:
		root = root.find('name')

	if root is not None:
		return root.text
	else :
		return ""

'''

recurse over all pom files of the module in the autorelease

'''
def recursePom(directoryName):

	global totalPoms

	recursePomInfo = []

	for dirName, subdirList, fileList in os.walk(directoryName):

		#skipping all src and target directories
	    if dirName=="src" or dirName=="target" or dirName==directoryName:
	    	continue;

	    for fname in fileList:

	        if fname == 'pom.xml' :

	        	totalPoms = totalPoms + 1

	        	# print dirName+'/'+fname
	        	pomExtractedInfo = {}
	        	pomExtractedInfo['path'] = dirName+'/'+'pom.xml'
	        	pomExtractedInfo['name'] = getPomName(dirName, fname)
	        	pomExtractedInfo['id'] = getUniqueId(dirName, fname)
	        	pomExtractedInfo['modules'] = getModuleNames(dirName, fname)
	        	pomExtractedInfo['dependencies'] = getDependencyNames(dirName, fname)
	        	pomExtractedInfo['parent'] = getParentNames(dirName, fname)

	        	recursePomInfo.append(pomExtractedInfo)

	        	# print pomExtractedInfo

	return recursePomInfo

'''

Tells whether the pom files exists or not

'''
def checkPomfileExistence(fname):
	return os.path.isfile(fname) 

#DIR_LOC = raw_input('Please enter the path of the autorelease folder: ')
DIR_LOC = '/var/www/html/gsoc/autorelease'

# stores all the dependency information in the dictionary
dependencies = {}
dependencies['path'] = DIR_LOC
dependencies['id'] = getUniqueId(DIR_LOC, "pom.xml")
dependencies['name'] = getPomName(DIR_LOC, "pom.xml")
dependencies['dependencies'] = getDependencyNames(DIR_LOC, "pom.xml")
dependencies['parent'] = getParentNames(DIR_LOC, "pom.xml")
dependencies['modules'] = getModuleNames(DIR_LOC, "pom.xml")
dependencies['moduleInfo'] = {}

actualModules = []

totalPoms = 0

for module in dependencies['modules']:

	moduleDir = DIR_LOC+'/'+module
	
	if checkPomfileExistence(moduleDir+"/pom.xml") :
		pass
	else :
		continue
	actualModules.append(module)
	dependencies['moduleInfo'][module] = {}
	dependencies['moduleInfo'][module]['id'] = getUniqueId(moduleDir, "pom.xml")
	dependencies['moduleInfo'][module]['name'] = getPomName(moduleDir, "pom.xml")
	dependencies['moduleInfo'][module]['modules'] = getModuleNames(moduleDir, "pom.xml")
	dependencies['moduleInfo'][module]['dependencies'] = getDependencyNames(moduleDir, "pom.xml")
	dependencies['moduleInfo'][module]['parent'] = getParentNames(moduleDir, "pom.xml")
	#array of all pom files information in the project
	dependencies['moduleInfo'][module]["recursePomInfo"] = recursePom(moduleDir)
	print totalPoms
	# print dependencies

# print dependencies
dependencies['modules'] = actualModules

print totalPoms


# def removeSingleQuote(data):
# 	return data.replace("'", "")

def getID(node):
	# return removeSingleQuote(node['id']['groupId'] + ':' + node['id']['artifactId'] + ':' + node['id']['version'])
	return node['id']['groupId'] + ':' + node['id']['artifactId'] + ':' + node['id']['version']

def filterInfo(name):
	return re.sub("org.opendaylight.", "", name)

def getDependencyID(node):
	# return removeSingleQuote(node['groupId'] + ':' + node['artifactId'] + ':' + node['version'])
	# return node['groupId'] + ':' + node['artifactId'] + ':' + node['version']
	return filterInfo( node['groupId'] )

'''

Return the project in which the module is found

'''

def findProjectOfModule(projectMapping, module):
	for key in projectMapping.keys():
		if module in projectMapping[key] :
			return key
		if module == key :
			return key
	return "unknown"

'''

TODO: Test for label later, would require change in the distinctIdLabel

'''

def getLabel(node):
	return getID(node)
	if node['name'] != "" :
		# return removeSingleQuote(node['name'])
		return node['name']
	else:
		return getID(node)

projectMappedToAllModules = {}

for project in actualModules :
	allModules = []
	allModules.extend(dependencies['moduleInfo'][project]['modules'])
	for data in dependencies['moduleInfo'][project]['recursePomInfo'] :
		allModules.extend(data['modules'])
		allModules.append(getID(data))
	projectMappedToAllModules[project] = allModules


projectMapping = 'var projectMapping = [\n'

for key in projectMappedToAllModules.keys() :
	projectMapping = projectMapping + '{projectName:\'' + key + '\','
	projectMapping = projectMapping + 'modules: ['
	for info in projectMappedToAllModules[key]:
		projectMapping = projectMapping + '\'' + info + '\','
	projectMapping = projectMapping[:-1]
	projectMapping = projectMapping + ']},\n'

projectMapping = projectMapping + '];\n'

# store the distinct module ids and labels

distinctIdLabel = []

distinctIdLabel.append(getID(dependencies))

for module in dependencies['modules'] :
	distinctIdLabel.append(getID(dependencies['moduleInfo'][module]))

	for submodule in dependencies['moduleInfo'][module]['recursePomInfo'] :
		distinctIdLabel.append(getID(submodule))

distinctIdLabel = set(distinctIdLabel)

#start of nodes json

anticipatedEdges = []
anticipatedNodes = []

distinctIdLabelFromEdges = []

dependencyMappedToProject = []

for module in dependencies['modules'] :
	# stringEdges = stringEdges + '{'
	# stringEdges = stringEdges + 'from: \'' + getID(dependencies) + '\','
	# stringEdges = stringEdges + 'to: \'' + getID(dependencies['moduleInfo'][module]) + '\''
	# stringEdges = stringEdges + '},\n'

	for submodule in dependencies['moduleInfo'][module]['recursePomInfo'] :
		for dependency in submodule['dependencies'] :
			distinctIdLabelFromEdges.append(findProjectOfModule( projectMappedToAllModules, getID(submodule) ))
			distinctIdLabelFromEdges.append(findProjectOfModule( projectMappedToAllModules, getDependencyID( dependency ) ))
			anticipatedEdges.append({
				# 'from': findProjectOfModule( projectMappedToAllModules, getID(submodule) ), 
				'from': module, 
				'to': findProjectOfModule( projectMappedToAllModules, getDependencyID( dependency ) ),
				'arrows': 'to'
			})
			dependencyMappedToProject.append({
				"dependency" : getDependencyID( dependency ),
				"project" : findProjectOfModule( projectMappedToAllModules, getDependencyID( dependency ) )
			})
		for dependency in submodule['parent'] :
			distinctIdLabelFromEdges.append(findProjectOfModule( projectMappedToAllModules, getID(submodule) ))
			distinctIdLabelFromEdges.append(findProjectOfModule( projectMappedToAllModules, getDependencyID( dependency ) ))
			anticipatedEdges.append({
				# 'from': findProjectOfModule( projectMappedToAllModules, getID(submodule) ), 
				'from': module, 
				'to': findProjectOfModule( projectMappedToAllModules, getDependencyID( dependency ) ),
				'arrows': 'to'
			})
			dependencyMappedToProject.append({
				"dependency" : getDependencyID( dependency ),
				"project" : findProjectOfModule( projectMappedToAllModules, getDependencyID( dependency ) )
			})

distinctIdLabelFromEdges = set(distinctIdLabelFromEdges)
for idLabel in distinctIdLabelFromEdges :
	anticipatedNodes.append({
		'id': idLabel,
		'label': idLabel
	})

#set of unique edges
anticipatedEdges = [dict(t) for t in set([tuple(d.items()) for d in anticipatedEdges])]

stringEdges = 'var edges = ' + json.dumps(anticipatedEdges) + '\n'

stringNodes = 'var nodes = '+ json.dumps(anticipatedNodes) + '\n'

f = open('../sidemenu/js/data.json', 'w')

f.write(stringNodes)

f.write(stringEdges)

f.write(projectMapping)

f.write(json.dumps(dependencyMappedToProject))

