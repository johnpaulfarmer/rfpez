<?php Section::inject('page_title', 'New Project') ?>
<?php Section::inject('no_page_header', true) ?>
<div class="new-project-page">
  <h4>New Project</h4>
  <div class="well">
    <p>
      <strong>Congrats on using EasyBid for your procurement!</strong>
    </p>
    <p>
      First, we just need some basic information about the project you're doing.
      If you can't find the correct project type in the list, <a href="#">contact us</a>
      and let us know.
    </p>
  </div>
  <form id="new-project-form" action="<?php echo Jade\Dumper::_text(route('projects')); ?>" method="POST">
    <div class="control-group">
      <label>Project Title</label>
      <input type="text" name="project[title]" />
    </div>
    <div class="control-group">
      <label>Agency</label>
      <input type="text" name="project[agency]" />
    </div>
    <div class="control-group">
      <label>Office</label>
      <input type="text" name="project[office]" />
    </div>
    <div class="control-group">
      <label>Bids Due</label>
      <span class="input-append date datepicker-wrapper">
        <input class="span3" type="text" name="proposals_due_at" />
        <span class="add-on">
          <i class="icon-calendar"></i>
        </span>
      </span>
      &nbsp; at 11:59pm EST
      <p>
        <em>You'll have a chance to change this later, so don't worry if you don't know the exact date.</em>
      </p>
    </div>
    <div class="control-group">
      <label>Project Type</label>
      <select id="project-type-select" name="project[project_type_id]">
        <?php foreach (ProjectType::defaults() as $project_type): ?>
          <option value="<?php echo Jade\Dumper::_text($project_type->id); ?>"><?php echo Jade\Dumper::_text($project_type->name); ?></option>
        <?php endforeach; ?>
        <option value="Other">Other</option>
      </select>
      <input id="new-project-type-input" class="hide" type="text" name="new_project_type_name" />
    </div>
    <div class="form-actions">
      <button class="btn btn-primary" type="submit">Create Project</button>
    </div>
  </form>
</div>