SELECT *
FROM formazioni
INNER JOIN schieramento
INNER JOIN giocatore ON schieramento.IdGioc = giocatore.IdGioc ON formazioni.IdFormazione = schieramento.IdFormazione
WHERE formazioni.IdFormazione =4