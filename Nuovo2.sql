SELECT *
FROM (
giocatore
INNER JOIN voto ON giocatore.idGioc = voto.idGioc
)
RIGHT JOIN (

SELECT idGiornata, ruolo, max( punti ) AS puntMax
FROM voto
INNER JOIN giocatore ON voto.idGioc = giocatore.idGioc
GROUP BY idGiornata, ruolo
)tb1 ON giocatore.ruolo = tb1.ruolo
AND voto.punti = tb1.puntMax
AND voto.idGiornata = tb1.idGiornata
ORDER BY voto.idGiornata, giocatore.ruolo