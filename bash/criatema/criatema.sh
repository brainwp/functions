#!/bin/bash
# uso: ./criatema.sh slug nome cpt=slug,singular,plural,[0|1],tema
if [ -z "$1" ]
	then 
	echo "
	# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
	# uso: ./criatema.sh slug nome cpt=slug,singular,plural,[0 para masculino |1 para feminino],tema
	# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
	"

	exit 1;
fi
# # $1 = pasta e nome do tema
diretorio=`pwd`;
echo $diretorio;
slug=$1;
nome=$2;
dir_instalacao="/Applications/MAMP/htdocs/rede";
dir_tema="/Applications/MAMP/htdocs/rede/wp-content/themes/$slug";

cd $dir_instalacao/wp-content/themes/;
git clone https://github.com/wpbrasil/odin.git $slug;

echo "">> $dir_tema/assets/js/main.js;
mkdir $dir_tema/assets/js/src/;
mv $dir_tema/assets/js/main.js $dir_tema/assets/js/src/main.js;

cd  $diretorio;
pwd;
cat ajax.mod > $dir_tema/inc/ajax-functions.php
cat javascript.mod > $dir_tema/assets/js/src/javascript.js
cp Gruntfile.js $dir_tema/src;
cd $dir_tema/src;
npm install;



cd $dir_tema/inc;
echo "<?php //Custom post types//" >> custom-post.php;
echo "<?php //Custom-fields" >> custom-fields.php;

cd ..;
echo "require_once get_template_directory() . '/inc/ajax-functions.php';" >> functions.php;
echo "require_once get_template_directory() . '/inc/custom-post.php';" >> functions.php;
echo "require_once get_template_directory() . '/inc/custom-fields.php';" >> functions.php;

sed -i 	-e 's/Theme Name: Odin/Theme Name: '"$nome"'/g' style.css;

cd $dir_instalacao;
site=`wp site create --slug=$slug --title="$nome" --porcelain`;
url=`wp site list --blog_id=$site --field=url`;
wp theme activate $slug --url=$url;


# echo $1;
# echo $2;
# # echo ${!var};

var=1;
# testa os argumentos
while [ "${!var}" != "" ]
do

	case ${!var} in 
		plugin*)
			# # caso seja um plugin
			# IFS="="
			# read -r -a array <<< "${!var}"
   #  		# pega o nome do plugin
   #  		plugin=${array[1]};;
   #  		#busca no repositorio
   #  		busca=`wp plugin search $plugin --field=slug --quiet`;
			# IFS=" "
			# read -r -a plugins <<< "$busca"
			# if 
			# for i in $plugins
			# do
			# 	echo "teste:"$i;
			# done

			# echo $teste;
			;;
		cpt*)
			IFS="="
			read -r -a array <<< "${!var}"
    		cpt=${array[1]};
    		IFS=","
			read -r -a cpt_arr <<< "$cpt"		
    		slug_cpt=${cpt_arr[0]}; 
    		singular_cpt=${cpt_arr[1]}; 
    		plural_cpt=${cpt_arr[2]}; 
    		genero_cpt=${cpt_arr[3]}; 
    		cd $diretorio;
    		./modulo_cpt.sh $slug_cpt $singular_cpt $plural_cpt $genero_cpt $slug $dir_tema
			;;

    		*)
	esac
  	# echo $var;
  	var=$(( $var + 1 ))
done