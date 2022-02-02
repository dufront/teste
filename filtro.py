print ("""
   __                 _   
  / _|               | |  
 | |_ _ __ ___  _ __ | |_ 
 |  _| '__/ _ \| '_ \| __|
 | | | | | (_) | | | | |_ 
 |_| |_|  \___/|_| |_|\__|
                          

 """)

with open('cpfsp.txt', 'r') as arquivo:
	for i in arquivo:
		if '[LIVE]' in i:
			print(i)
			arquivo = open('lista.txt','a')
			arquivo.write(i)

with open('lista.txt', 'r') as arquivo:
	for i in arquivo:
		a=i.replace(' - ', ' » ')
		arquivo = open('formatado.txt','a')
		arquivo.write(a)
		print (a)

