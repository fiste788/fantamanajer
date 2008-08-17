SELECT giocatore.IdGioc, Cognome, Nome, Ruolo, IdSquadra, Club, AVG( Voto ) , SUM( Presenza ) , SUM( Gol ) , SUM( Assist )
FROM giocatore
INNER JOIN voti ON giocatore.IdGioc = voti.IdGioc
WHERE IdSquadra =1
GROUP BY giocatore.IdGioc
