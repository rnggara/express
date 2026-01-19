<div class="form-group">
    <label for="" class="col-form-label">You can write about your years of experience, industry, or skills. People also talk about their achievements or previous job experiences</label>
    <textarea name="about" class="form-control" cols="30" rows="10">{!! Auth::user()->about !!}</textarea>
</div>
@csrf
