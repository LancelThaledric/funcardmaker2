Il s'agit de faire N masques qui partitionnent le canvas, et s'additionnent parfaitement.

Horizontal et vertical :

- Outil dégradé. On calcule l'espace à partitionner (L = largeur/hauteur moins bordures)
- On pose L = nA + (n-1)T (A et T pour Aplat et Transition)
- On sait que A est proportionnel à T :              Horizontal : A = 1.397849 T		Vertical : A = 1.4 T
- On résoud d'équation ci-dessus pour obtenir T = xxx pixels, on en déduit A.
- On place les guides sur le canvas, et on place chaque dégradé.

Radial :

- Outil dégradé radial. On admet que A = 1.5 T
- On partitionne un tour d'image comme suit (exemple pour 3 couleurs)
- T AAA TT AAA TT AAA T (AAA représente la largeur d'un aplat, TT celle d'une transition, T celle d'une demi-transition)
- On créé les différentes modèles de dégradés respectant ce schéma, pour les N aplats
- On applique chaque dégradé en partant su centre, vers le haut de l'image.