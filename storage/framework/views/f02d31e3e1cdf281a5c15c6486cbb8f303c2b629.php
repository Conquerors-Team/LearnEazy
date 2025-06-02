<?php $__env->startSection('content'); ?>
  <main id="main">

      <!-- ======= Book List ======= -->
      <section>
        <div class="container">

          <div class="section-title">
            <h3>List of <span>Reference Books</span></h3>
            <p>Ut possimus qui ut temporibus culpa velit eveniet modi omnis est adipisci expeditam.</p>
          </div>

          <table class="table">
            <thead>
              <tr>
                <th scope="col" width="1%">#</th>
                <th scope="col">Book</th>
                <th scope="col">Author</th>
                <th scope="col" width="1%">Download</th>
              </tr>
            </thead>
            <tbody>
              <?php

              $books = \App\ReferenceBook::all();
              $i = 1;
              foreach ($books as $key => $value) {

              ?>
              <tr>
                <td><?php echo e($i); ?></td>
                <td><strong><?php echo e($value->title); ?></strong></td>
                <td><?php echo e($value->author_name); ?></td>
                <td class="text-center"><a href="<?php echo e(route('site.media-file-download', ['model' => 'ReferenceBook', 'field' => 'file_input', 'record_id' => $value->id])); ?>" target="_blank"><i class="icofont-download"></i></a></td>
              </tr>
            <?php $i++; } ?><!--
              <tr>
                <td>2</td>
                <td><strong>Book 2 name</strong></td>
                <td>Author 2 name</td>
                <td class="text-center"><i class="icofont-download"></i></td>
              </tr>
              <tr>
                <td>3</td>
                <td><strong>Book 3 name</strong></td>
                <td>Author 3 name</td>
                <td class="text-center"><i class="icofont-download"></i></td>
              </tr>
              <tr>
                <td>4</td>
                <td><strong>Book 4 name</strong></td>
                <td>Author 4 name</td>
                <td class="text-center"><i class="icofont-download"></i></td>
              </tr> -->
            </tbody>
          </table>

        </div>
      </section> <!-- End About Section -->

    </main><!-- End #main -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.sitelayoutnew', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>