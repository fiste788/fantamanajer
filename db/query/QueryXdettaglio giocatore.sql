SELECT Cognome, Ruolo, Club, voti.IdGioc, Voto, IdPosizione
FROM voti
INNER JOIN schieramento ON voti.IdGioc = schieramento.IdGioc
INNER JOIN giocatore ON voti.IdGioc = giocatore.IdGioc
WHERE schieramento.IdFormazione =3
AND voti.IdGiornata =26
ORDER BY IdPosizione
