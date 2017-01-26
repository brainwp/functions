#!/bin/bash
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# uso: mod_cpt slug singular plural  [0|1] tema diretorio_tema 								#
# cria um cpt com as seguintes caracteristicas 												#
# singular: o nome do cpt no singular 														#
# plural: o nome do cpt no plural 															#
# 0 significa que o nome é masculino e 1 feminino											# 
# tema: o tema a ser adicionado o textdomain (opcional, padrão:odin)						# 
# diretorio_tema: diretorio do tema a ser criado os cpts.									# 
# O arquivo diretorio_tema/inc/custom-post.php deve ser requerido no functions pela linha:  # 
# require_once get_template_directory() . '/inc/custom-post.php';							# 
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 

if [ -z "$1" ]
	then 
	echo "
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
# uso: mod_cpt slug singular plural  [0|1] tema diretorio_tema 								
# cria um cpt com as seguintes caracteristicas																				
# singular: o nome do cpt no singular 														
# plural: o nome do cpt no plural 															
# 0 significa que o nome é masculino e 1 feminino											
# tema: o tema a ser adicionado o textdomain (opcional, padrão:odin)						
# diretorio_tema: diretorio do tema a ser criado os cpts.									
# O arquivo diretorio_tema/inc/custom-post.php deve ser requerido no functions pela linha:  
# require_once get_template_directory() . '/inc/custom-post.php';							
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
";
	exit 1;
fi

slug=$1;
singular=$2;
plural=$3;
genero=$4;
tema=$5;
diretorio_tema=$6;
if [ -z "$tema" ];
	then
		tema="odin"
fi
if [ $genero  -eq 0 ]
	then
		tod="Todos"
		nov="Novo"
		nenh="Nenhum"

	else
		tod="Todas"
		nov="Nova"
		nenh="Nenhuma"

fi

 sed 's/SINGULAR/'$singular'/g' cpt.mod | 
 sed 's/PLURAL/'$plural'/g' | 
 sed 's/SLUG/'$slug'/g'  | 
 sed 's/TOD/'$tod'/g' | 
 sed 's/NENH/'$nenh'/g' | 
 sed 's/NOV/'$nov'/g' | 
 sed 's/TEMA/'$tema'/g' >> $diretorio_tema/inc/custom-post.php

