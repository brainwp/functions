# criatema
# uso: ./criatema.sh slug nome cpt=slug,singular,plural,[0|1],tema,dir_tema 

cript de bash para criar um tema e um site em uma instalação local multisite
editar a linha :

dir_instalacao="/Applications/MAMP/htdocs/rede";

do arquivo criatema.sh com o endereco da instalação multi-site 

A criação dos cpts é feita chamando o arquivo modulo_cpt.sh que também pode ser chamado separadamente para criar novos cpts da seguinte forma:
		# uso: mod_cpt slug singular plural  [0|1] tema diretorio_tema
		# singular: o nome do cpt no singular
		# plural: o nome do cpt no plural
		# 0 significa que o nome é masculino e 1 feminino
		# tema: o tema a ser adicionado o textdomain (opcional, padrão:odin)
		# diretorio_tema: diretorio do tema a ser criado os cpts.
		# O arquivo diretorio_tema/inc/custom-post.php deve ser requerido no functions pela linha:
		# require_once get_template_directory() . '/inc/custom-post.php';
