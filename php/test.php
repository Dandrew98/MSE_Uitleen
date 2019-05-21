 <?php

require "config.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

//wat is er uitgeleend en wie
/**$query = "SELECT k.klant_nr, p.naam, pd.type, pd.omschrijving, pd.reeks, pd.artikel_nr, pd.defect, u.datum_uitleen, u.datum_retour, u.opmerkingen FROM tbl_klant k, tbl_product p
JOIN tbl_product_detail pd on p.id = pd.product_id
join tbl_uitlenen u on pd.id = u.id_product_detail where k.klant_nr = u.id_klant";**/
//alle producten laten zien die niet in uitleen staan
$query = "SELECT p.naam, pd.type, pd.omschrijving, pd.reeks, pd.artikel_nr, pd.defect FROM tbl_uitlenen u, tbl_product p
JOIN tbl_product_detail pd on p.id = pd.product_id where pd.id != u.id_product_detail";

$uitlees = mysqli_query($mysqli, $query);

if (mysqli_num_rows($uitlees) > 0)
{

	echo "<table border='1'>";

	// start en tabelrij voor de knopjes
	echo "<tr>";

	//maak de cellen voor de kopjes
	echo "<th>Naam</th>";
	echo "<th>Type</th>";
	echo "<th>Omschrijving</th>";
	echo "<th>Reeks</th>";
	echo "<th>Artikel nummer</th>";
	echo "<th>Defect</th>";

	// sluit de tabelrij voor de kopjes
	echo "</tr>";

	// loop door alle rijen data heen
	while ($row = mysqli_fetch_array($uitlees)) {
		//aparte vars
		$defect = $row['defect'];
		// start een tabelrij
		echo "<tr>";

		//if else voor defect
		if ($defect == 0)
		{
			$defect = "Nee";
			//maak de cellen voor de gegevens
			echo "<td>" . $row['naam'] . "</td>";
			echo "<td>" . $row['type'] . "</td>";
			echo "<td>" . $row['omschrijving'] . "</td>";
			echo "<td>" . $row['reeks'] . "</td>";
			echo "<td>" . $row['artikel_nr'] . "</td>";
			echo "<td>" . $defect . "</td>";

		}
		else
		{
			//maak de cellen voor de gegevens
			echo "<td>" . $row['naam'] . "</td>";
			echo "<td>" . $row['type'] . "</td>";
			echo "<td>&nbsp;</td>";
			echo "<td>" . $row['reeks'] . "</td>";
			echo "<td>" . $row['artikel_nr'] . "</td>";
			echo "<td>&nbsp;</td>";
			echo "<td>&nbsp;</td>";
			$defect = "Ja";
			echo "<td>" . $defect . "</td>";
		}


		// sluit de tabelrij
		echo"</tr>";

	}

	//sluit de tabel
	echo "</table>";


}

?>
