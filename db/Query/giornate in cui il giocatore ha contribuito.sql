-- visione giornata per giornata

SELECT *
FROM voti 
LEFT JOIN
(
 schieramento INNER JOIN formazioni ON schieramento.idformazione=formazioni.idformazione
) ON (voti.IdGioc=schieramento.Idgioc AND voti.Idgiornata=formazioni.idgiornata)
WHERE voti.idgioc=342
GROUP BY voti.Idgiornata

--statistiche quando ha contribuito

SELECT count(considerato) as NumSchierato,AVG(Voto) as mediaPunti, SUM(Voto) as Punti
FROM voti
LEFT JOIN (
schieramento
INNER JOIN formazioni ON schieramento.idformazione = formazioni.idformazione
) ON ( voti.IdGioc = schieramento.Idgioc
AND voti.Idgiornata = formazioni.idgiornata )
WHERE voti.idgioc =710
AND idUtente=5
AND considerato=1