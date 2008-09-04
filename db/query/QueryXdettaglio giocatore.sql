SELECT giocatore.IdGioc, Cognome, Voto, Ruolo, Club, IdPosizione, Considerato, IdGiornata, IdFormazione
FROM (
schieramento
INNER JOIN giocatore ON schieramento.IdGioc = giocatore.IdGioc
)
LEFT JOIN voti ON giocatore.IdGioc = voti.IdGioc
WHERE schieramento.IdFormazione = (
SELECT IdFormazione
FROM formazioni
WHERE IdGiornata = '1'
AND IdSquadra = '2' )
ORDER BY IdPosizione