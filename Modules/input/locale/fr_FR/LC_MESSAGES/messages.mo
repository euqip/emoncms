��    D      <  a   \      �     �     �     �     �       y        �     �     �     �     �     �     �  	             (     4     ;     K     `     l  ]   q  �   �     S  	   Y     c     r     �     �  �   �  #   E	     i	  �   p	     <
     I
     V
     n
     }
  	   �
  	   �
     �
     �
     �
     �
     �
     �
  	   �
     �
     �
                  
   #     .  !   G  �   i     2  	   F  �   P       $        2     P     ]     p     u     �  !  �     �  	   �     �     �  &   �  �   �     �     �     �     �     �  *   �     *     E     U     i  	   w      �  #   �     �     �  _   �  �   9     �     �     �     �  0         B  �   c  &   �       �   '            !   ,     N     l  
   �  
   �  1   �     �     �  *   �          	          #     2     C     J     V     m  *   �  /   �  �   �     �  #   �  �        �  K   �  #        8     G     V     Z  
   s               6   "   #             (      
       :                 !   *              >         9   /           <           C                .                =      -          ?                 $       %       @   7   '   4   5           )   1               A       ,          	      +                 3      2       8   &   0       D         ;   B           + input - input / input Actions Add an input process All the numbers after the first two are data values. The first node here (node 10) has three data values: 250,100 and 20. Allow negative Allow positive Apikey authentication Arg Assign inputs to a node group Available HTML URLs Available JSON commands Bulk data CREATE NEW: CSV format: Delete Delete an input Delete input process Description Edit For example using the first json type request above just add the apikey to the end like this: If you want to call any of the following actions when your not logged in, add an apikey to the URL of your request: &apikey=APIKEY. Input Input API Input API Help Input actions Input configuration:    Input process actions Input processes are executed sequentially with the result being passed back for further processing by the next processor in the input processing list. Input processing configuration page Inputs Inputs is the main entry point for your monitoring device. Configure your device to post values here, you may want to follow the <a href="api">Input API helper</a> as a guide for generating your request. JSON format: Last updated List input process list List of inputs Log to feed Max value Min value Move input process Move up New No inputs created Node: Order Post data Power to kWh Power to kWh/d Process Process list Read & Write: Read only: Reset input process list Set the input entry time manually The first number of each node is the time offset, so for the first node it is 0 which means the packet for the first node arrived at 0 seconds. The second node arrived at 2 seconds and 3rd 10 seconds. The input list view This page To post data from a remote device you will need to include in the request url your write apikey. This give your device write access to your emoncms account, allowing it to post data. Value You can provide data using bulk mode You have no processes defined kWh to kWh/d kWh to kWh/d (OLD) name update feed @time x input Project-Id-Version: emoncms
Report-Msgid-Bugs-To: 
POT-Creation-Date: 2014-01-14 21:27+0100
PO-Revision-Date: 2014-01-14 21:27+0100
Last-Translator: Baptiste Gaultier <b.gaultier@gmail.com>
Language-Team: Baptiste Gaultier (Télécom Bretagne) <baptiste.gaultier@telecom-bretagne.eu>
Language: fr_FR
MIME-Version: 1.0
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 8bit
X-Poedit-KeywordsList: _;gettext;gettext_noop
X-Poedit-Basepath: .
X-Poedit-SourceCharset: utf-8
X-Generator: Poedit 1.5.4
X-Poedit-SearchPath-0: ../../..
 + source  - source / source Actions Ajouter un traitement sur les données Tous les nombres qui suivent les deux premiers sont les valeurs de chaque mesure à enregistrer. Par exemple, ici le nœud 10 contient 3 valeurs (250,100 et 20). Négatif autorisé Positif autorisé Clés d'API Param&egrave;tre Associer la source à un nœud Liste des pages HTML pour la configuration Commandes JSON disponibles Données brutes Créer un nouveau : Format CSV :  Supprimer Supprimer une source de données Supprimer un traitement de données Description Editer Par exemple, pour utiliser l'appel json ci-dessus, il suffit d'ajouter la clé API comme suit : Si vous souhaitez utiliser les liens ci-dessous alors que vous n'êtes pas connecté, veuillez ajouter votre clé d'API à la fin de l'url : <b>&apikey=APIKEY</b>. Sources API Sources Aide de l'API Liste des sources Définition des traitements sur vos données n° Traitements des données reçues Les processus de traitement de données sont exécutés séquentiellement. Le résultat de chaque étape est passé en entrée de l'étape suivante. Page de configuration des traitements  Sources Les sources sont le point d'entrée pour vos capteurs. Vos capteurs doivent être configurés pour envoyer leurs données ici, veuillez consulter l'<a href="api">Aide de l'API</a> afin de g&eacute;n&eacute;rer votre requ&ecirc;te. Format JSON :  Dernière MàJ Liste des processus de traitement Liste des sources de données stocker dans un flux valeur Max Valeur Min Changer la position d'un traitement dans la liste Monter Nouveau Aucune source de données n'est disponible Nœud Ordre Données transmises watts vers kWh watts vers kWh/j Action Traitements Accès en écriture :  Accès en lecture seule :  Réinitialiser les processus de traitement Modifier manuellement l'horodatage des données Le premier paramètre de chaque nœud est le décalage (exprimé en seconde). Dans cet exemple : le paquet pour le premier nœud est arrivé à 0 seconde. Le second nœud est arrivé à 2 secondes et le 3ème à 10 secondes. Liste des Sources de données Cette page (documentation de l'API) Pour envoyer des données depuis un périphérique distant, vous devez ajouter votre clé d'API à la fin de l'url. Cela permet de donner les accès en écriture à votre périphérique. Valeur Vous pouvez transmettre vos données de manière brute avec un appel global Vous n'avez aucun processus défini kWh vers kWh/j kWh vers kWh/j Nom actualiser le flux @date  × source 