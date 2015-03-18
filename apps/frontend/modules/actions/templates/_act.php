<div class="left_pain_actions" id="acttions">
  <h1 class="ui-widget-header">Actions</h1>
  <div id="list_actions">    
    <h2><a href="#">General</a></h2>
    <div>
      <ul class="rectangle-list">        
        <?php foreach ($actArr as $key => $val): ?>        
        <li id="act"><?= $key ?></li>        
        <?php endforeach; ?>        
      </ul>
    </div>      
  </div>
</div>
