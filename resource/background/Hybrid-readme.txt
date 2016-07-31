Il s'agit de faire N masques qui partitionnent le canvas, et s'additionnent parfaitement.

Horizontal et vertical :

- Outil d�grad�. On calcule l'espace � partitionner (L = largeur/hauteur moins bordures)
- On pose L = nA + (n-1)T (A et T pour Aplat et Transition)
- On sait que A est proportionnel � T :              Horizontal : A = 1.397849 T		Vertical : A = 1.4 T
- On r�soud d'�quation ci-dessus pour obtenir T = xxx pixels, on en d�duit A.
- On place les guides sur le canvas, et on place chaque d�grad�.

Radial :

- Outil d�grad� radial. On admet que A = 1.5 T
- On partitionne un tour d'image comme suit (exemple pour 3 couleurs)
- T AAA TT AAA TT AAA T (AAA repr�sente la largeur d'un aplat, TT celle d'une transition, T celle d'une demi-transition)
- On cr�� les diff�rentes mod�les de d�grad�s respectant ce sch�ma, pour les N aplats
- On applique chaque d�grad� en partant su centre, vers le haut de l'image.