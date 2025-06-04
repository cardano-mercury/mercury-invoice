# Laravel 11 to Laravel 12 Upgrade Plan

## Project Overview
- **Current Version**: Laravel 11.x
- **Target Version**: Laravel 12.x
- **Estimated Upgrade Time**: 30-45 minutes
- **Project Type**: CardanoMercury Invoice System
- **Deployment**: Docker Container (`cardanomercury-web`)

---

## Phase 1: Dependency Updates (High Impact)

### Step 1.1: Update Core Laravel Dependencies
**Objective**: Update primary Laravel framework dependencies
**Files to modify**: `application/composer.json`

**Actions**:
1. Update `laravel/framework` from `^11.0` to `^12.0`
2. Update `pestphp/pest` from `^2.0` to `^3.0` 
3. Add PHPUnit 11 requirement (current project uses Pest, but may need PHPUnit as base dependency)

**Expected composer.json changes**:
```json
"require": {
    "laravel/framework": "^12.0",
    // ... other dependencies remain same
},
"require-dev": {
    "pestphp/pest": "^3.0",
    "pestphp/pest-plugin-laravel": "^3.0", // Update this too if available
    // ... other dev dependencies
}
```

**Validation**: Run dependency check:
```bash
docker exec cardanomercury-web bash -c "composer update --dry-run"
```

### Step 1.2: Install Updated Dependencies
**Objective**: Install the updated dependencies
**Command**: 
```bash
docker exec cardanomercury-web bash -c "composer update"
```

**Potential Issues to Handle**:
- Dependency conflicts with third-party packages
- Memory limit issues during update
- Missing package versions

**Fallback Strategy**: If conflicts arise, update dependencies incrementally:
```bash
docker exec cardanomercury-web bash -c "composer update laravel/framework"
docker exec cardanomercury-web bash -c "composer update pestphp/pest"
```

### Step 1.3: Clear Application Caches
**Objective**: Clear all Laravel caches after dependency update
**Commands**:
```bash
docker exec cardanomercury-web bash -c "php artisan config:clear"
docker exec cardanomercury-web bash -c "php artisan cache:clear"
docker exec cardanomercury-web bash -c "php artisan route:clear"
docker exec cardanomercury-web bash -c "php artisan view:clear"
docker exec cardanomercury-web bash -c "php artisan optimize:clear"
```

---

## Phase 2: Framework-Specific Updates (Medium Impact)

### Step 2.1: Check for UUID Usage (Medium Impact)
**Objective**: Verify if project uses UUID traits that changed in Laravel 12
**Status**: ✅ **ANALYSIS COMPLETE** - No UUID traits found in current codebase
**Action Required**: None - project doesn't use HasUuids trait

**Note for Future**: If UUID functionality is added later, remember:
- `HasUuids` now defaults to UUIDv7 (ordered UUIDs)
- Use `HasVersion4Uuids` for legacy UUIDv4 behavior

### Step 2.2: Verify Carbon 3 Compatibility
**Objective**: Ensure application works with Carbon 3.x (automatic with Laravel 12)
**Status**: Low risk - Carbon 3 is mostly backward compatible
**Action Required**: Test date/time functionality after upgrade

**Areas to Test**:
- Date formatting in views
- Date calculations in business logic
- API date responses
- Database date queries

---

## Phase 3: Application Code Review (Low Impact)

### Step 3.1: Review Image Validation Rules
**Objective**: Check for image validation that may now exclude SVGs
**Status**: ✅ **ANALYSIS COMPLETE** - No image validation rules found
**Action Required**: None currently

**Note for Future**: If image validation is added:
- Default `image` rule now excludes SVGs
- Use `image:allow_svg` or `File::image(allowSvg: true)` to allow SVGs

### Step 3.2: Review Request Merging Behavior
**Objective**: Check for `mergeIfMissing()` usage that may behave differently
**Status**: ✅ **ANALYSIS COMPLETE** - No mergeIfMissing usage found
**Action Required**: None currently

**Note for Future**: `mergeIfMissing()` now supports dot notation for nested arrays

---

## Phase 4: Testing and Validation

### Step 4.1: Update PHPUnit Configuration (if needed)
**Objective**: Ensure PHPUnit configuration is compatible
**Current Status**: Using Pest 2.x, upgrading to Pest 3.x
**Files to check**: `application/phpunit.xml`

**Actions**:
1. Verify PHPUnit XML schema is compatible
2. Test that all test suites run correctly
3. Check for any deprecated PHPUnit features

### Step 4.2: Run Full Test Suite
**Objective**: Verify all tests pass after upgrade
**Commands**:
```bash
docker exec cardanomercury-web bash -c "php artisan test"
# or if using PHPUnit directly:
docker exec cardanomercury-web bash -c "vendor/bin/phpunit"
```

**Critical Test Areas**:
- Authentication (Laravel Sanctum/Jetstream)
- Inertia.js integration
- Horizon queue processing
- API endpoints
- Database operations

### Step 4.3: Manual Application Testing
**Objective**: Verify critical application features work correctly
**Test Areas**:
1. User registration/login
2. Invoice creation and management
3. Payment processing (Stripe integration)
4. Cardano payment functionality
5. Email sending (Resend integration)
6. File uploads/downloads
7. API endpoints

**Health Check Command**:
```bash
docker exec cardanomercury-web bash -c "php artisan about"
```

---

## Phase 5: Configuration and Optimization

### Step 5.1: Update Configuration Files
**Objective**: Sync configuration files with Laravel 12 defaults
**Action**: Compare with fresh Laravel 12 installation configs

**Files to review**:
- `config/app.php`
- `config/database.php`
- `config/queue.php`
- `config/mail.php`

**Configuration Check**:
```bash
docker exec cardanomercury-web bash -c "php artisan config:show"
```

### Step 5.2: Check for New Laravel 12 Features
**Objective**: Review if any new Laravel 12 features should be adopted
**Areas to investigate**:
- New Eloquent features
- Performance improvements
- New validation rules
- Security enhancements

**Check Laravel Version**:
```bash
docker exec cardanomercury-web bash -c "php artisan --version"
```

### Step 5.3: Update Documentation and Dependencies List
**Objective**: Document the upgrade and update project documentation
**Actions**:
1. Update `README.md` with Laravel 12 requirement
2. Update deployment scripts if needed
3. Notify team of upgrade completion

---

## Phase 6: Deployment Preparation

### Step 6.1: Update Docker Configuration
**Files to check**: `application/staging.Dockerfile`, `application/production.Dockerfile`
**Objective**: Ensure Docker images are compatible with Laravel 12

### Step 6.2: Update Vapor Configuration
**File**: `application/vapor.yml`
**Objective**: Verify Laravel Vapor configuration works with Laravel 12

### Step 6.3: Environment-Specific Testing
**Objective**: Test in staging environment before production
**Actions**:
1. Deploy to staging environment
2. Run full regression testing
3. Performance testing
4. Load testing (if applicable)

**Database Migration Check**:
```bash
docker exec cardanomercury-web bash -c "php artisan migrate:status"
```

**Queue Worker Status**:
```bash
docker exec cardanomercury-web bash -c "php artisan horizon:status"
```

---

## Rollback Plan

### Emergency Rollback Procedure
**If critical issues are discovered:**
1. Revert `composer.json` changes
2. Run dependency restoration:
   ```bash
   docker exec cardanomercury-web bash -c "composer install"
   ```
3. Clear all caches:
   ```bash
   docker exec cardanomercury-web bash -c "php artisan optimize:clear"
   ```
4. Restart application services (restart Docker container if needed)

### Safe Rollback Points
- After Phase 1: Dependency updates only
- After Phase 2: Framework updates complete
- After Phase 4: Testing complete

---

## Post-Upgrade Monitoring

### Step 7.1: Monitor Application Performance
**Duration**: 48-72 hours
**Monitor**:
- Response times
- Memory usage
- Database query performance
- Queue processing times
- Error rates

**Monitoring Commands**:
```bash
docker exec cardanomercury-web bash -c "php artisan about"
docker exec cardanomercury-web bash -c "php artisan horizon:status"
docker logs cardanomercury-web --tail=100
```

### Step 7.2: User Feedback Collection
**Actions**:
- Monitor user reports
- Check error logs:
  ```bash
  docker exec cardanomercury-web bash -c "tail -f storage/logs/laravel.log"
  ```
- Verify payment processing
- Test Cardano integration

---

## Risk Assessment

### High Risk Areas
- **Third-party Package Compatibility**: Some packages may not be Laravel 12 ready
- **Pest 3.0 Changes**: Testing framework upgrade may introduce test failures
- **Production Dependencies**: Vapor, Horizon, Sanctum compatibility
- **Docker Container Dependencies**: Ensure all services work post-upgrade

### Medium Risk Areas
- **Date/Time Functionality**: Carbon 3 changes
- **Custom Middleware**: Verify compatibility
- **API Responses**: Check for any breaking changes

### Low Risk Areas
- **Basic Laravel Features**: Routing, controllers, views
- **Database Operations**: Standard Eloquent usage
- **Front-end Assets**: Inertia.js and Vite configuration

---

## Success Criteria

### Upgrade Complete When:
- [ ] All dependencies updated successfully
- [ ] All tests passing
- [ ] Application runs without errors
- [ ] All major features functional
- [ ] Performance metrics within acceptable range
- [ ] No critical user-facing issues
- [ ] Docker container operates normally

### Quality Gates:
1. **Phase 1 Complete**: Dependencies updated, no composer conflicts
2. **Phase 2 Complete**: Framework changes addressed
3. **Phase 4 Complete**: All tests passing
4. **Phase 6 Complete**: Staging deployment successful
5. **Final**: Production deployment successful and stable

---

## Docker-Specific Considerations

### Container Management:
- **Before Upgrade**: Ensure container has sufficient resources (memory/disk)
- **During Upgrade**: Monitor container performance during composer update
- **After Upgrade**: Verify all services within container are working

### Useful Docker Commands:
```bash
# Check container status
docker ps | grep cardanomercury-web

# Monitor container resources
docker stats cardanomercury-web

# Container logs
docker logs cardanomercury-web -f

# Execute interactive shell (for debugging)
docker exec -it cardanomercury-web bash
```

---

## Notes for AI Agent Execution

### Autonomous Execution Guidelines:
1. **Stop and Report** if any tests fail during Phase 4
2. **Stop and Report** if composer update fails with conflicts
3. **Continue with Caution** for minor deprecation warnings
4. **Document All Changes** made during the upgrade process
5. **Preserve All Custom Code** - only update framework-related dependencies
6. **Monitor Docker Container** throughout the process

### Required Manual Review Points:
- After Phase 1 (dependency updates)
- After Phase 4 (testing complete)
- Before Phase 6 (deployment preparation)

### Docker Command Format:
All commands should use: `docker exec cardanomercury-web bash -c "COMMAND"`

### Emergency Contacts:
- Development Team Lead
- DevOps Engineer (for deployment issues)
- Project Stakeholders (for business impact)
