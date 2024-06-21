<?php 

  require_once './app/mardownParser.php';
  
  $directory = './letter-boxes';
  $selected_file = null;
  $selected_file_content = '';
  $files = array();

  if ($dh = opendir($directory)) {
    while (($file = readdir($dh)) !== false) {
        if ($file != '.' && $file != '..' && is_file($directory . '/' . $file)) {
            $files[] = $file;
        }
    }
    closedir($dh);
  } else {
      die("Could not open directory.");
  }


  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['file'])) {
    $selected_file = $_POST['file'];
  } else {
      if (!empty($files)) {
          $selected_file = $files[0];
      }
  }

  if ($selected_file !== null) {
    $file_path = $directory . '/' . $selected_file;

    if (is_file($file_path) && is_readable($file_path)) {
        $selected_file_content = parseFrontMatter($file_path);
    } else {
        $selected_file_content = 'The selected file is not readable or does not exist.';
    }
}
?>

<letter>

  <header>
    <address>
      <from>
        <name><?= $name ?></name>
        <street><?= $street ?></street>
        <city><?= $city ?></city>
        <?php if(!empty($country)): ?>
        <country><?= $country ?></country>
        <?php endif ?>
      </from>
      <to contenteditable>
        <?= $selected_file_content["frontMatter"]["toCompany"]?><br>
        <?= $selected_file_content["frontMatter"]["toAP"]?><br>
        <?= $selected_file_content["frontMatter"]["toStreet"]?><br>
        <?= $selected_file_content["frontMatter"]["toCity"]?>
      </to>
    </address>
  </header>

  <main>
    <subject contenteditable> <?= $selected_file_content["frontMatter"]["subject"]?></subject>
    <date contenteditable><?= $date ?></date>
    <text contenteditable>

    <form action="" class="hidden-print" method="post">
        <label for="file">Choose a letter-box template:</label>
        <select name="file" id="file">
            <?php
            foreach ($files as $file) {
                $selected = ($file === $selected_file) ? ' selected' : '';
                echo '<option value="' . htmlspecialchars($file) . '"' . $selected . '>' . htmlspecialchars($file) . '</option>';
            }
            ?>
        </select>
        <input type="submit" value="Submit">
    </form>
    <?= $selected_file_content['content'] ?>
    </text>
    <signature>
      <closing contenteditable><?= $closing ?></closing>
      <name contenteditable><?= $name ?></name>
      <?php if($signature): ?>
      <img src="<?= $signature ?>">
      <?php endif ?>
    </signature>
  </main>

  <footer>
    
    <address>
      <name><?= $name ?></name>
      <street><?= $street ?></street>
      <city><?= $city ?></city>
      <?php if(!empty($country)): ?>
      <country><?= $country ?></country>
      <?php endif ?>
    </address>
    
    <contact>
      
      <?php if(!empty($phone)): ?>
      <phone>
        <label><?= $labels['phone'] ?></label> <?= $phone ?>
      </phone>
      <?php endif ?>

      <?php if(!empty($mobile)): ?>
      <mobile>
        <label><?= $labels['mobile'] ?></label> <?= $mobile ?>
      </mobile>
      <?php endif ?>
      
      <?php if(!empty($email)): ?>
      <email>
        <label><?= $labels['email'] ?></label> <?= $email ?>
      </email>
      <?php endif ?>

      <?php if(!empty($website)): ?>
      <website>
        <label><?= $labels['website'] ?></label> <?= $website ?>
      </website>
      <?php endif ?>
    
    </contact>
    
    <?php if($bank !== false): ?>
    <bank>

      <?php if(!empty($bank)): ?>
      <name>
        <label><?= $labels['bank'] ?></label> <?= $bank ?>
      </name>
      <?php endif ?>

      <?php if(!empty($iban)): ?>
      <iban>
        <label><?= $labels['iban'] ?></label> <?= $iban ?>
      </iban>
      <?php endif ?>

      <?php if(!empty($bic)): ?>
      <bic>
        <label><?= $labels['bic'] ?></label> <?= $bic ?>
      </bic>
      <?php endif ?>

    </bank>
    <?php endif ?>

    <?php if(!empty($vatId) || !empty($taxId)): ?>
    <info>

      <?php if(!empty($vatId)): ?>
      <vatid>
        <label><?= $labels['vatId'] ?></label> <?= $vatId ?>
      </vatid>
      <?php endif ?>

      <?php if(!empty($taxId)): ?>
      <taxid>
        <label><?= $labels['taxId'] ?></label> <?= $taxId ?>
      </taxid>
      <?php endif ?>

    </info>
    <?php endif ?>

  </footer>
</letter>

<selectiontools id="selectiontools">
  <button id="btn-bold"><strong>B</strong></button>
  <button id="btn-italic"><em>I</em></button>
  <script src="app/js/selectiontools.js"></script>
</selectiontools>